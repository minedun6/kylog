<?php

namespace App\DataTables;

use Carbon\Carbon;
use Yajra\Datatables\Services\DataTable;
use App\Repositories\Backend\Stock\StockRepository;
use Illuminate\Contracts\View\Factory;
use Yajra\Datatables\Datatables;

class FiltredStockByArticleDataTable extends DataTable
{

    protected $stock;

    protected $printPreview = 'backend.layouts.print.print';

    public function __construct(Datatables $datatables, Factory $viewFactory, StockRepository $stock)
    {
        parent::__construct($datatables, $viewFactory);
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
                if ($request->has('supplier')) {
                    $query->collection = $query->collection->where('supplier_id', '=', $request->get('supplier'));
                }
            }, true)
            ->editColumn('supplier_name', function ($packageItem) {
                return "<span class='label label-info'>{$packageItem->supplier_name}</span>";
            })
            ->editColumn('quantity', function ($packageItem) {
//                dd($packageItem);
                $used = !empty($packageItem->delivery_qty) ? $packageItem->delivery_qty : 0;
                $total = !empty($packageItem->quantities) ? $packageItem->quantities : 0;

                if ($packageItem->delivery_qty == null) {
                    return $total . '/' . $total . ' ' . ((boolean)$packageItem->piece ? "Pieces" : $packageItem->unit);
                } else {
                    if ($used > $total) {
                        return 0 . '/' . $total . ' ' . ((boolean)$packageItem->piece ? "Pieces" : $packageItem->unit);
                    } else {
                        return $total - $used . '/' . $total . ' ' . ((boolean)$packageItem->piece ? "Pieces" : $packageItem->unit);
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
        $request = $this->request();
        $company = access()->user()->company;

        if ($request->has('filterDate')) {
            $date = Carbon::parse($request->get('filterDate'));
            $query = $this->stock->getForFiltredDataTable($company, $date);
        } else {
            $query = $this->stock->getStockByArticleForDataTable($company);
        }

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
                'dom' => "Bfrip",
                'pagingType' => 'bootstrap_extended',
                'responsive' => true,
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
            'product_reference',
            'designation',
            'supplier_name',
            'quantity'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'filtred_stock_by_article_table_' . time();
    }
}
