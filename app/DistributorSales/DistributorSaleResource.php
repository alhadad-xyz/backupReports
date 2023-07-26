<?php

namespace App\DistributorSales;

use \koolreport\dashboard\Client;

use \koolreport\dashboard\admin\Resource;

use \koolreport\dashboard\admin\actions\DeleteAction;
use \koolreport\dashboard\admin\actions\DetailAction;
use \koolreport\dashboard\admin\actions\InlineEditAction;
use \koolreport\dashboard\admin\actions\UpdateAction;

use \koolreport\dashboard\fields\ID;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Currency;
use \koolreport\dashboard\fields\Calculated;

use \koolreport\dashboard\containers\Modal;
use \koolreport\dashboard\containers\Inline;
use \koolreport\dashboard\containers\Panel;
use \koolreport\dashboard\containers\Row;

use \koolreport\dashboard\inputs\Button;
use \koolreport\dashboard\inputs\Dropdown;
use \koolreport\dashboard\inputs\Select;

use \koolreport\dashboard\menu\MenuItem;
use App\AutoMaker;
use App\DistributorSales\CityFilter;
use App\DistributorSales\CountryFilter;
use App\DistributorSales\DistributorSaleTable;
use App\DistributorSales\DistributorSaleChart;
use App\DistributorSales\DistributorSaleLine;
use App\Distributors\DistributorDetailLineChart;
use App\Distributors\DistributorDetailLineChart2;
use App\Distributors\DistributorDetailTable;
use App\Distributors\DistributorDetailTable2;
use App\Distributors\DistributorDetailPieChart;
use App\Distributors\DistributorDetailPieChart2;

class DistributorSaleResource extends Resource
{
    protected function onCreated()
    {
        $this->manageTable("distributors")->inSource(AutoMaker::class);

        //Allow searchBox
        $this->listScreen()->searchBox()
            ->enabled(true)
            ->placeHolder("Search ...");

        $this->listScreen()->glassBox()
            ->type("primary");

        $this->listScreen()->createButton()
            ->enabled(false);
        
        $this->listScreen()->actionBox()
            ->enabled(false);

        $this->listScreen()->adminTable()
            ->tableStriped(true);
            
        $this->detailScreen()->title(function(){
          $data = $this->data();
          return $data["distributor_name"];
        });

        $this->detailScreen()->bottom(function($id){
            //$id is the id value of record that shown by detail screen
            $thirty_days_ago = date('Y-m-d', strtotime("-31 days"));
            return  [
                Panel::create()->header("Sales Last 30 Days")->type("info")->sub([
                    DistributorDetailLineChart::create()
                        ->dataSource(
                            AutoMaker::table("distributors")
                            ->leftJoin('transactions', 'transactions.distributor_id', 'distributors.distributor_id')
                            ->where('transactions.distributor_id', $id)
                            ->where('invoice_date', '>=', $thirty_days_ago)
                            ->select("invoice_date", "COUNT('transactions.id') AS `Total Sales`")
                            ->groupBy('invoice_date')
                        ),
                ])->width(1),

                Panel::create()->header("Sales Current Financial Year")->type("info")->sub([
                    DistributorDetailLineChart2::create()
                    ->dataSource(
                        AutoMaker::table("distributors")
                        ->leftJoin('transactions', 'transactions.distributor_id', 'distributors.distributor_id')
                        ->where('transactions.distributor_id', $id)
                        ->select('MONTHNAME(invoice_date) as Month', 'COUNT(transactions.id) as `Total Sales`')
                        ->groupBy('Month')
                    ),
                ])->width(1),

                Panel::create()->header("Total Sales Per Outlet")->type("info")->sub([
                    DistributorDetailTable::create()
                    ->dataSource(
                        AutoMaker::table("outlets")
                        ->join('distributors', 'distributors.distributor_id', 'outlets.distributor_id')
                        ->leftJoin('transactions', 'transactions.outlet_id', 'outlets.outlet_id')
                        ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
                        ->join('products', 'transaction_detail.product_id', 'products.id')
                        ->where('outlets.distributor_id', $id)
                        ->select('products.productName as `Nama`', 'outlets.outlet_address as `Alamat`', 'outlets.outlet_city as `Kota`', 'transactions.grand_total as `Total Sale`', 'transactions.sale_return as `Total Sale Return`', 'transactions.due_payment as `Due Payment`')
                    ),
                ])->width(2/3),

                Panel::create()->header("Total Sales Per Outlet")->type("info")->sub([
                    DistributorDetailPieChart::create()
                    ->dataSource(
                        AutoMaker::table("outlets")
                        ->join('distributors', 'distributors.distributor_id', 'outlets.distributor_id')
                        ->leftJoin('transactions', 'transactions.outlet_id', 'outlets.outlet_id')
                        ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
                        ->join('products', 'transaction_detail.product_id', 'products.id')
                        ->where('outlets.distributor_id', $id)
                        ->select('products.productName as `Nama`', 'transaction_detail.qty as `Qty`')
                    ),
                ])->width(1/3),

                Panel::create()->header("Total Sales Per Distributor")->type("info")->sub([
                    DistributorDetailTable2::create()
                    ->dataSource(
                        AutoMaker::table("distributors")
                        ->leftJoin('transactions', 'transactions.distributor_id', 'distributors.distributor_id')
                        ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
                        ->join('products', 'transaction_detail.product_id', 'products.id')
                        ->where('distributors.distributor_id', $id)
                        ->select('products.productName as `Nama`', 'distributors.distributor_city as `Kota`', 'transactions.grand_total as `Total Sale`', 'transactions.sale_return as `Total Sale Return`', 'transactions.due_payment as `Due Payment`')
                    ),
                ])->width(2/3),

                Panel::create()->header("Total Sales Per Distributor")->type("info")->sub([
                    DistributorDetailPieChart2::create()
                    ->dataSource(
                        AutoMaker::table("distributors")
                        ->leftJoin('transactions', 'transactions.distributor_id', 'distributors.distributor_id')
                        ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
                        ->join('products', 'transaction_detail.product_id', 'products.id')
                        ->where('distributors.distributor_id', $id)
                        ->select('products.productName as `Nama`', 'transaction_detail.qty as `Qty`')
                    ),
                ])->width(1/3),

                // Panel::create()->header("List Outlet")->type("info")->sub([
                //     DistributorDetailOutletTable::create()
                //     ->dataSource(
                //         AutoMaker::table("outlets")
                //         ->join('distributors', 'distributors.distributor_id', 'outlets.distributor_id')
                //         ->where('outlets.distributor_id', $id)
                //         ->select('outlets.outlet_id as `No`', 'outlets.outlet_name as `Nama`', 'outlets.outlet_city as `Kota`', 'outlets.outlet_address as `Alamat`', 'outlets.outlet_contact_no as `No Telp`', 'outlets.outlet_email as `Email`', 'outlets.outlet_taxable_company as `Nama Pengusaha Kena Pajak`', 'outlets.outlet_npwp_address as `Alamat NPWP`', 'outlets.outlet_npwp_no as `No NPWP`')
                //     ),
                // ])->width(1),
            ];
        });
    }

    protected function query($query) {
        $query
        ->leftJoin('transactions', 'transactions.distributor_id', 'distributors.distributor_id')
        ->select("transactions.id", "SUM(transactions.grand_total) as grand_total", "SUM(transactions.sale_return) as sale_return", "SUM(transactions.due_payment) as due_payment")
        ->select('distributors.distributor_id', "distributor_name", 'distributor_city', 'distributor_address')
        ->groupBy('transactions.distributor_id');
        return $query;
    }

    protected function filters()
    {
        return [
            DateOrderFilter::create()->title("Tanggal"),
            CityFilter::create()->title("Kota"),
            CountryFilter::create()->title("Negara"),
        ];
    }

    // protected function glasses()
    // {
    //     return [
    //         DistributorSale::create()
    //     ];
    // }

    protected function actions()
    {
        return [
            DetailAction::create()->showOnTable(true),
            UpdateAction::create()->showOnTable(false),
            InlineEditAction::create()->showOnTable(false),
            DeleteAction::create()->showOnTable(false),
        ];
    }


    protected function fields()
    {
        return [
            Calculated::create("#", function($row) {
                static $index = 0;
                $index++;
                return $index/2;
            }),
            ID::create("ID Distributor")
                ->colName('distributor_id')
                ->showOnIndex(false),
            Text::create("Nama")
                ->colName('distributor_name')
                ->searchable(true)
                ->sortable(true),
            Text::create("Kota")
                ->colName("distributor_city")
                ->searchable(true)
                ->sortable(true),
            Currency::create("Total Sale")->IDR()->symbol()
                ->colName("grand_total")
                ->searchable(true)
                ->sortable(true),
            Currency::create("Total Sale Return")->IDR()->symbol()
                ->colName("sale_return")
                ->searchable(true)
                ->sortable(true),
            Currency::create("Due Payment")->IDR()->symbol()
                ->colName("due_payment")
                ->searchable(true)
                ->sortable(true),
        ];
    }

    protected function highlights()
    {
        return [
            Panel::success([
                DistributorSaleChart::create()
                    ->xlsxExportable(true)
                    ->csvExportable(true)
            ])
            ->width(1)
            ->header("Chart Total Sales By Distributors"),

            // Panel::success([
            //     DistributorSaleLine::create()
            //         ->xlsxExportable(true)
            //         ->csvExportable(true)
            // ])
            // ->width(1/2)
            // ->header("Distributors Sales Last 30 Days"),

            Button::create()->text("Export")->onClick(function(){
                return Modal::show("largeModal");
            }),

            Modal::create("largeModal")->title("Export & Import Report")->sub([
                Inline::create([
                    Dropdown::create("exportOptions")
                    ->title("<i class='far fa-file-pdf'></i> Export")
                    ->items([
                        "PDF Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("DistributorSaleTable")->exportToPDF("Report Distributor Sales " . date('Y-m-d His'))
                            ),
                        "Excel Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("DistributorSaleTable")->exportToXLSX("Report Distributor Sales " . date('Y-m-d His'))
                            ),
                        "CSV Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("DistributorSaleTable")->exportToCSV("Report Distributor Sales " . date('Y-m-d His'))
                            ),
                    ]),

                ]),

                DistributorSaleTable::create()
                    ->pdfExportable(true)
                    ->xlsxExportable(true)
                    ->csvExportable(true),
            ])->size("lg"),
        ];
    }
}
