<?php

namespace App\Http\Controllers\Backend\Statistics;

use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Delivery\DeliveryRepository;
use App\Repositories\Backend\Reception\PackageRepository;
use App\Repositories\Backend\Reception\ReceptionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    /**
     * @var ReceptionRepository
     */
    private $reception;
    /**
     * @var DeliveryRepository
     */
    private $delivery;

    /**
     * @var PackageRepository
     */
    private $package;
    /**
     * @var CompanyRepository
     */
    private $company;

    /**
     * StatisticsController constructor.
     * @param ReceptionRepository $reception
     * @param DeliveryRepository $delivery
     * @param PackageRepository $package
     * @param CompanyRepository $company
     */
    public function __construct(ReceptionRepository $reception, DeliveryRepository $delivery, PackageRepository $package, CompanyRepository $company)
    {
        $this->reception = $reception;
        $this->delivery = $delivery;
        $this->package = $package;
        $this->company = $company;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.statistics.index');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getDetailedStats(Request $request)
    {
        $company = $this->company->find($request->get('company'));
        $year = $request->get('year');

        $received = $this->package->getReceivedData($company, $year);
        $delivered = $this->package->getDeliveredData($company, $year);

        $totalPackagesReceived = $received->sum(function ($item) {
            return $item['packages_number'];
        });

        $totalPiecesReceived = $received->sum(function ($item) {
            return $item['pieces_number'];
        });

        $totalPackagesDelivered = $delivered->sum(function ($item) {
            return $item['packages_number'];
        });

        $totalPiecesDelivered = $delivered->sum(function ($item) {
            return $item['pieces_number'];
        });

        return response()->json([
            'received' => $received,
            'delivered' => $delivered,
            'totalPackagesReceived' => $totalPackagesReceived,
            'totalPiecesReceived' => $totalPiecesReceived,
            'totalPackagesDelivered' => $totalPackagesDelivered,
            'totalPiecesDelivered' => $totalPiecesDelivered,
            'ReceivedPackageDataForChart' => $this->processPackagesDataForChart($totalPackagesReceived, $received),
            'DeliveredPackageDataForChart' => $this->processPackagesDataForChart($totalPackagesDelivered, $delivered),
            'ReceivedPiecesDataForChart' => $this->processPiecesDataForChart($totalPiecesReceived, $received),
            'DeliveredPiecesDataForChart' => $this->processPiecesDataForChart($totalPiecesDelivered, $delivered),
        ]);
    }

    /**
     * @param $total
     * @param $data
     * @return array
     */
    private function processPackagesDataForChart($total, $data)
    {
        $result = [];
        foreach ($data as $key => $row) {
            $result[$key]['name'] = $row['month'];
            if ($total != 0)
                $result[$key]['y'] = ($row['packages_number'] / $total) * 100;
            else
                $result[$key]['y'] = 0;
        }
        return $result;
    }

    /**
     * @param $total
     * @param $data
     * @return array
     */
    private function processPiecesDataForChart($total, $data)
    {
        $result = [];
        foreach ($data as $key => $row) {
            $result[$key]['name'] = $row['month'];
            if ($total != 0)
                $result[$key]['y'] = ($row['pieces_number'] / $total) * 100;
            else
                $result[$key]['y'] = 0;
        }
        return $result;
    }

}
