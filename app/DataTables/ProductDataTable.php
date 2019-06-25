<?php

namespace App\DataTables;

use App\Repositories\Backend\Product\ProductRepository;
use App\User;
use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Services\DataTable;

class ProductDataTable extends DataTable
{

    protected $product;

    protected $printPreview = 'backend.layouts.print.print';

    public function __construct(Datatables $datatables, Factory $viewFactory, ProductRepository $product)
    {
        parent::__construct($datatables, $viewFactory);
        $this->product = $product;
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
            ->eloquent($this->query())
            ->editColumn('sap', function ($product) {
                return $product->getSAP();
            })
            ->editColumn('value', function ($product) {
                return $product->getValue();
            })
            ->editColumn('net_weight', function ($product) {
                return $product->getNetWeight();
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('reference')) {
                    $query->where('products.reference', '=', $request->get('reference'));
                }

                if ($request->has('supplier_reference')) {
                    $query->where('products.supplier_reference', '=', $request->get('supplier_reference'));
                }

                if ($request->has('product')) {
                    $query->where('products.id', '=', $request->get('product'));
                }

                if ($request->has('supplier')) {
                    $query->where('suppliers.id', '=', $request->get('supplier'));
                }

                if ($request->has('value')) {
                    $config = explode('-', config('kylogger.values_intervals.' . $request->get('value')));
                    $min = (int)$config[0];
                    $max = (int)$config[1];
                    $query->whereBetween('products.value', [$min, $max]);
                }

                if ($request->has('net_weight')) {
                    $config = explode('-', config('kylogger.net_weight_intervals.' . $request->get('net_weight')));
                    $min = (int)$config[0];
                    $max = (int)$config[1];
                    $query->whereBetween('products.net_weight', [$min, $max]);
                }
            }, true)
            ->addColumn('actions', function ($product) {
                return $this->getActionsButtons($product);
            })
            ->filterColumn('reference', function ($q, $keyword) {
                $q->where('products.reference', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('supplier_reference', function ($q, $keyword) {
                $q->where('products.supplier_reference', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('sap', function ($q, $keyword) {
                $q->where('products.sap', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('designation', function ($q, $keyword) {
                $q->where('products.designation', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('name', function ($q, $keyword) {
                $q->where('suppliers.name', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('value', function ($q, $keyword) {
                $q->where('products.value', 'LIKE', '%' . $keyword . '%');
            })
            ->filterColumn('net_weight', function ($q, $keyword) {
                $q->where('products.net_weight', 'LIKE', '%' . $keyword . '%');
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
        $query = $this->product->getForDataTable(access()->user()->company);

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        if (access()->allow('create-product')) {
            $buttons = [
                'createProduct',
                ['extend' => 'reload', 'className' => 'btn xs default', 'text' => '<i class="fa fa-refresh"></i>'],
                ['extend' => 'print', 'className' => 'btn xs default', 'text' => '<i class="fa fa-print"></i>'],
                ['extend' => 'excel', 'className' => 'btn xs default', 'text' => '<i class="fa fa-file-excel-o"></i>'],
                ['extend' => 'pdf', 'className' => 'btn xs default', 'text' => '<i class="fa fa-file-pdf-o"></i>'],
            ];
        } else {
            $buttons = [
                ['extend' => 'reload', 'className' => 'btn xs default', 'text' => '<i class="fa fa-refresh"></i>'],
                ['extend' => 'print', 'className' => 'btn xs default', 'text' => '<i class="fa fa-print"></i>'],
                ['extend' => 'excel', 'className' => 'btn xs default', 'text' => '<i class="fa fa-file-excel-o"></i>'],
                ['extend' => 'pdf', 'className' => 'btn xs default', 'text' => '<i class="fa fa-file-pdf-o"></i>'],
            ];
        }
        return $this->builder()
            ->columns($this->getColumns())
            ->parameters([
                'dom' => "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><t><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
                'buttons' => $buttons,
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
                }'
            ])->setTableAttribute(['class' => 'table dataTable no-footer table-bordered table-condensed', 'width' => '100%']);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id' => ['title' => '# Id'],
            'reference' => ['title' => 'Reference'],
            'supplier_reference' => ['title' => 'Supplier Reference'],
            'sap' => ['title' => 'Code SAP'],
            'designation' => ['title' => 'Designation'],
            'name' => ['title' => 'Supplier'],
            'value' => ['title' => 'Value'],
            'net_weight' => ['title' => 'Net Weight'],
            //'brut_weight',
            //'piece',
            //'unit',
            'actions' => ['sortable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false, 'searchable' => false]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'products_' . time();
    }

    /**
     * @param $product
     *
     * @return mixed
     */
    protected function getActionsButtons($product)
    {
        return $this->getShowButton($product) .
            $this->getEditButton($product);
    }

    /**
     * @param $product
     *
     * @return string
     */
    protected function getEditButton($product)
    {
        return '<a class="btn btn-xs btn-warning" href="' . route('admin.product.edit', $product) . '"><i class="fa fa-pencil"></i></a>';
    }

    /**
     * @param $product
     * @return string
     */
    protected function getShowButton($product)
    {
        return '<a class="btn btn-xs btn-info" href="' . route('admin.product.show', $product) . '"><i class="fa fa-eye"></i></a>';
    }

}
