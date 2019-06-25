<?php

namespace App\DataTables\Stock;

use App\Repositories\Backend\Reception\PackageRepository;
use App\Repositories\Backend\Stock\StockRepository;
use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Services\DataTable;

class StockByArticleDataTable extends DataTable
{
    protected $package;

    protected $stock;

    protected $printPreview = 'backend.layouts.print.print';

    /**
     * StockDataTable constructor.
     *
     * @param Datatables $datatables
     * @param Factory $viewFactory
     * @param PackageRepository $package
     * @param StockRepository $stock
     */
    public function __construct(Datatables $datatables, Factory $viewFactory, PackageRepository $package, StockRepository $stock)
    {
        parent::__construct($datatables, $viewFactory);
        $this->package = $package;
        $this->stock = $stock;
    }


    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $request = $this->request();
        return $this->datatables
            ->collection($this->query())
            ->filter(function ($query) use ($request) {
                if ($request->has('product_reference')) {
                    $query->collection = $query->collection->where('product_reference', '=', $request->get('product_reference'));
                }

                if ($request->has('product')) {
                    $query->collection = $query->collection->where('product_id', '=', $request->get('product'));
                }

                if ($request->has('supplier')) {
                    $query->collection = $query->collection->where('supplier_id', '=', $request->get('supplier'));
                }

                if ($request->has('client')) {
                    $query->where('clients.id', '=', $request->get('client'));
                }

            }, true)
//            ->filterColumn('product_reference', function ($q, $keyword) {
//                $q->collection = null;
//            })
//            ->filterColumn('designation', function ($q, $keyword) {
//                $q->where('products.designation', 'LIKE', '%' . $keyword . '%');
//            })
//            ->filterColumn('supplier_name', function ($q, $keyword) {
//                $q->where('suppliers.name', 'LIKE', '%' . $keyword . '%');
//            })
//            ->filterColumn('client_name', function ($q, $keyword) {
//                $q->where('clients.name', 'LIKE', '%' . $keyword . '%');
//            })
            ->editColumn('supplier_name', function ($packageItem) {
                return "<span class='label label-info'>{$packageItem->supplier_name}</span>";
            })
            ->editColumn('quantities', function ($packageItem) {
                $used = !empty($packageItem->delivery_qty) ? $packageItem->delivery_qty : 0;
                $total = !empty($packageItem->quantities) ? $packageItem->quantities : 0;

                if ($packageItem->delivery_qty == null) {
                    return $total . '/' . $total . ' ' . ((boolean) $packageItem->piece ? "Pieces" : $packageItem->unit);
                } else {
                    if ($used > $total) {
                        return 0 . '/' . $total . ' ' . ((boolean) $packageItem->piece ? "Pieces" : $packageItem->unit);
                    } else {
                        return $total - $used . '/' . $total . ' ' . ((boolean) $packageItem->piece ? "Pieces" : $packageItem->unit);
                    }
                }
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()

    {
        $company = access()->user()->company;

        $query = $this->stock->getStockByArticleForDataTable($company);

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->parameters([
                'dom' => "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><t><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
                'buttons' => [
                    'customFilter',
                    ['extend' => 'reload', 'className' => 'btn xs default', 'text' => '<i class="fa fa-refresh"></i>'],
                    ['extend' => 'print', 'className' => 'btn xs default', 'text' => '<i class="fa fa-print"></i>'],
                    ['extend' => 'excel', 'className' => 'btn xs default', 'text' => '<i class="fa fa-file-excel-o"></i>'],
                    ['extend' => 'pdf', 'className' => 'btn xs default', 'text' => '<i class="fa fa-file-pdf-o"></i>'],
                ],
                'pagingType' => 'bootstrap_extended',
                'responsive' => true,
                'saveState' => true,
                'stateSave' => true,
                'stateDuration' => '-1',
                "stateSaveCallback" => 'function (settings, data) {           
                   sessionStorage.setItem("receptions-table", JSON.stringify(data));
                }',
                "stateLoadCallback" => 'function (settings) {
                    if (Boolean(sessionStorage.getItem("receptions-table"))) {
                        var state = JSON.parse(sessionStorage.getItem("receptions-table")) ;
                        return state;
                    }                
                }',
            ])->setTableAttribute(['class' => 'table dataTable no-footer table-bordered table-condensed collapsed', 'width' => '100%']);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'product_reference' => ['title' => 'Product Reference'],
            'designation',
            'supplier_name' => ['title' => 'Supplier'],
            'quantities' => ['title' => 'Remaining Qty', 'searchable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'stock_by_article_' . time();
    }
}
