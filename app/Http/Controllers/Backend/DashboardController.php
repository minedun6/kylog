<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Delivery\DeliveryRepository;
use App\Repositories\Backend\Reception\ReceptionRepository;
use Spatie\Activitylog\Models\Activity;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{

    protected $reception;

    protected $delivery;

    /**
     * DashboardController constructor.
     * @param ReceptionRepository $reception
     * @param DeliveryRepository $delivery
     */
    public function __construct(ReceptionRepository $reception, DeliveryRepository $delivery)
    {
        $this->reception = $reception;
        $this->delivery = $delivery;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('backend.dashboard');
    }
}
