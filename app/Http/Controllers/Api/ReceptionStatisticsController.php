<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Delivery\DeliveryRepository;
use App\Repositories\Backend\Reception\ReceptionRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ReceptionStatisticsController extends Controller
{
    protected $reception;

    protected $company;

    /**
     * ReceptionStatisticsController constructor.
     * @param ReceptionRepository $reception
     * @param CompanyRepository $company
     */
    public function __construct(ReceptionRepository $reception, CompanyRepository $company)
    {
        $this->reception = $reception;
        $this->company = $company;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $period = $request->has('period') ? $request->get('period') : null;

        $start = Carbon::parse(config("kylogger.stat_period.{$period}"));

        $end = Carbon::now();

        $query = $this->company->query();
        $query->whereHas('clientReceptions');
        $query->with(['clientReceptions' => function ($q) use ($start, $end, $period) {
            if (isset($period)) {
                return $q->whereBetween('receptions.reception_date', [$start, $end]);
            } else {
//                return $q->whereYear('receptions.created_at', Carbon::now()->year);
                return $q->orderBy('receptions.reception_date', 'desc');
            }
        }]);
        $query->withCount(['clientReceptions' => function ($q) use ($start, $end, $period) {
            if (isset($period)) {
                return $q->whereBetween('receptions.reception_date', [$start, $end]);
            } else {
//                return $q->whereYear('receptions.created_at', Carbon::now()->year);
                return $q->orderBy('receptions.reception_date', 'desc');
            }
        }]);
        $query->orderBy('client_receptions_count', 'desc');
        $data = $query->get();

        $result = [];
        foreach ($data as $dataEntry) {
            $result[] = [
                'name' => $dataEntry->name,
                'y' => (int)$dataEntry->client_receptions_count,
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

        $company = $this->company->query()->withCount(['clientReceptions' => function ($q) {
//            return $q->whereYear('receptions.created_at', Carbon::now()->year);
            return $q->orderBy('receptions.reception_date', 'desc');
        }])->find($drilldown);

        $query = $this->company->query();
        $query->find($drilldown);
        $query->with(['clientReceptions' => function ($q) use ($start, $end) {
            if (isset($period)) {
                return $q->whereBetween('receptions.reception_date', [$start, $end]);
            } else {
//                return $q->whereYear('receptions.created_at', Carbon::now()->year);
                return $q->orderBy('receptions.reception_date', 'desc');
            }
        }]);
        $query->withCount(['clientReceptions' => function ($q) use ($start, $end) {
            if (isset($period)) {
                return $q->whereBetween('receptions.reception_date', [$start, $end]);
            } else {
//                return $q->whereYear('receptions.created_at', Carbon::now()->year);
                return $q->orderBy('receptions.reception_date', 'desc');
            }
        }]);

        $data = $query->first();

        $receptions = $data->clientReceptions->sortByDesc('reception_date');

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

            foreach ($receptions as $v) {
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
