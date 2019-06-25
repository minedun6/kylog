<?php namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Delivery\Delivery;
use App\Repositories\Backend\Company\CompanyRepository;
use App\Repositories\Backend\Delivery\DeliveryRepository;
use App\Transformers\DeliveryTransformer;
use App\Transformers\PackageTransformer;
use App\Transformers\ProductTransformer;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{

    /**
     * @var DeliveryRepository
     */
    protected $delivery;
    /**
     * @var CompanyRepository
     */
    protected $company;


    /**
     * DeliveryController constructor.
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
     * @return
     */
    public function get(Request $request)
    {
        if ($request->has('company') && !empty($request->get('company')) && !is_null($request->get('company'))) {
            $company = $this->company->find($request->get('company'));
        }
        $deliveries = $this->delivery->query();
        $deliveries->whereMonth('deliveries.delivery_order_date', '=', $request->get('month'));
        $deliveries->whereYear('deliveries.delivery_order_date', '=', $request->get('year'));
        if (isset($company)) {
            if ($company->isSupplier()) {
                $deliveries->leftJoin('delivery_supplier', 'delivery_supplier.delivery_id', '=', 'deliveries.id');
                $deliveries->where('delivery_supplier.supplier_id', '=', $company->id);
            } else if ($company->isClient()) {
                $deliveries->where('deliveries.client_id', '=', $company->id);
            }
        }
        $count = $deliveries->get();

        return $count->count();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function latest(Request $request)
    {
        $company = null;
        if ($request->has('company') && !empty($request->get('company')) && !is_null($request->get('company'))) {
            $company = $this->company->find($request->get('company'));
        }
        return $this->delivery->transform(5, $company);
    }

    /**
     * @param Delivery $delivery
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Delivery $delivery)
    {
        $this->delivery->delete($delivery);
        return response(['response' => 'success'], 200);
    }

    /**
     * @param Delivery $delivery
     * @return Delivery
     */
    public function show(Delivery $delivery)
    {
        return fractal()
            ->item($delivery, new DeliveryTransformer())
            ->parseIncludes(['packageItems.product', 'packageItems.package'])
            ->toJson();
    }

    /**
     * @param Delivery $delivery
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePackageItem(Delivery $delivery, $id, Request $request)
    {
        $packageItem = $delivery->packageItems()->wherePivot('package_product_id', $id)->first();
        $packageItem->used_qty -= $packageItem->pivot->qty;
        $packageItem->save();
        $delivery->packageItems()->wherePivot('package_product_id', $id)->detach($id);
        return response()->json(['response' => 'success']);
    }

}