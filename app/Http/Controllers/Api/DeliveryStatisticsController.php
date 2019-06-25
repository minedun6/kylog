<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Delivery\DeliveryRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeliveryStatisticsController extends Controller
{

    protected $delivery;

    protected $company;

    /**
     * DeliveryStatisticsController constructor.
     * @param DeliveryRepository $delivery
     * @param CompanyRepository $company
     */
    public function __construct(DeliveryRepository $delivery, CompanyRepository $company)
    {
        $this->delivery = $delivery;
        $this->company = $company;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $period = $request->has('period') ? $request->get('period') : null;

        $start = Carbon::parse(config("kylogger.stat_period.{$period}"));

        $end = Carbon::now();

        $query = $this->company->query();
        $query->whereHas('clientDeliveries');
        $query->with(['clientDeliveries' => function ($q) use ($start, $end, $period) {
            if (isset($period)) {
                return $q->whereBetween('deliveries.delivery_order_date', [$start, $end]);
            } else {
                return $q->whereYear('deliveries.delivery_order_date', Carbon::now()->year);
            }
        }]);
        $query->withCount(['clientDeliveries' => function ($q) use ($start, $end, $period) {
            if (isset($period)) {
                return $q->whereBetween('deliveries.delivery_order_date', [$start, $end]);
            } else {
                return $q->whereYear('deliveries.delivery_order_date', Carbon::now()->year);
            }
        }]);
        $query->orderBy('client_deliveries_count', 'desc');

        $data = $query->get();

        $result = [];
        foreach ($data as $dataEntry) {
            $result[] = [
                'name' => $dataEntry->name,
                'y' => (int)$dataEntry->client_deliveries_count,
                'drilldown' => (int)$dataEntry->id
            ];
        }
        return $result;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function drilldown(Request $request)
    {
        $period = $request->has('period') ? $request->get('period') : null;

        $start = Carbon::parse(config("kylogger.stat_period.{$period}"))->format('Y-m-d');

        $end = Carbon::now()->format('Y-m-d');

        $drilldown = $request->has('drilldown') ? $request->get('drilldown') : null;

        $company = $this->company->query()->withCount(['clientDeliveries' => function ($q) {
            return $q->whereYear('deliveries.delivery_order_date', Carbon::now()->year);
        }])->find($drilldown);

        $query = $this->company->query();
        $query->find($drilldown);
        $query->with(['clientDeliveries' => function ($q) use ($start, $end) {
            if (isset($period)) {
                return $q->whereBetween('deliveries.delivery_order_date', [$start, $end]);
            } else {
                return $q->whereYear('deliveries.delivery_order_date', Carbon::now()->year);
            }
        }]);
        $query->withCount(['clientDeliveries' => function ($q) {
            if (isset($period)) {
                return $q->whereBetween('deliveries.delivery_order_date', [$start, $end]);
            } else {
                return $q->whereYear('deliveries.delivery_order_date', Carbon::now()->year);
            }
        }]);

        $data = $query->first();

        $deliveries = $data->clientDeliveries->sortByDesc('delivery_order_date');

        $result = [];

        $year = date('Y');

        $result['id'] = (int)$drilldown;
        $result['name'] = $company->name;

        for ($i = 1; $i <= 12; $i++) {
            if ($i < 10) {
                $month = "0$i";
            } else {
                $month = "$i";
            }
            $date = "$year-$month";

            $value = 0;

            foreach ($deliveries as $v) {
                if (date('Y-m', strtotime($v->created_at)) == $date) {
                    $value += 1;
                } else {
                    $value += 0;
                }
            }

            $date_get = 'Y-m';
            $label = date($date_get, strtotime("$year-$month"));

            $result['data'][] = [
                $label,
                (int)$value
            ];
        }
        return $result;
    }

}
