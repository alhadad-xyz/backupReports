<?php

namespace App\OutletSales;

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
use App\OutletSales\CityFilter;
use App\OutletSales\CountryFilter;
use App\OutletSales\OutletSaleTable;
use App\OutletSales\OutletSaleChart;
use App\OutletSales\OutletSaleLine;
use App\Outlets\OutletDetailLineChart;
use App\Outlets\OutletDetailLineChart2;
use App\Outlets\OutletDetailTable;
use App\Outlets\OutletDetailTable2;
use App\Outlets\OutletDetailPieChart;
use App\Outlets\OutletDetailPieChart2;

class OutletSaleResource extends Resource
{
    protected function onCreated()
    {
        $this->manageTable("outlets")->inSource(AutoMaker::class);

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
            return $data["outlet_name"];
        });

        $this->detailScreen()->bottom(function($id){
            //$id is the id value of record that shown by detail screen
            $thirty_days_ago = date('Y-m-d', strtotime("-31 days"));
            return  [
                Panel::create()->header("Sales Last 30 Days")->type("info")->sub([
                    OutletDetailLineChart::create()
                        ->dataSource(
                            AutoMaker::table("outlets")
                            ->leftJoin('transactions', 'transactions.outlet_id', 'outlets.outlet_id')
                            ->where('transactions.outlet_id', $id)
                            ->where('invoice_date', '>=', $thirty_days_ago)
                            ->select("invoice_date", "COUNT('transactions.id') AS `Total Sales`")
                            ->groupBy('invoice_date')
                        ),
                ])->width(1),

                Panel::create()->header("Sales Current Financial Year")->type("info")->sub([
                    OutletDetailLineChart2::create()
                    ->dataSource(
                        AutoMaker::table("outlets")
                        ->leftJoin('transactions', 'transactions.outlet_id', 'outlets.outlet_id')
                        ->where('transactions.outlet_id', $id)
                        ->select('MONTHNAME(invoice_date) as Month', 'COUNT(transactions.id) as `Total Sales`')
                        ->groupBy('Month')
                    ),
                ])->width(1),

                Panel::create()->header("Total Sales Per Customer")->type("info")->sub([
                    OutletDetailTable::create()
                    ->dataSource(
                        AutoMaker::table("customers")
                        ->join('outlets', 'outlets.outlet_id', 'customers.outlet_id')
                        ->leftJoin('transactions', 'transactions.customer_id', 'customers.customer_id')
                        ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
                        ->join('products', 'transaction_detail.product_id', 'products.id')
                        ->where('customers.outlet_id', $id)
                        ->select('products.productName as `Nama`', 'customers.customer_address as `Alamat`', 'customers.customer_city as `Kota`', 'transactions.grand_total as `Total Sale`', 'transactions.sale_return as `Total Sale Return`', 'transactions.due_payment as `Due Payment`')
                    ),
                ])->width(2/3),

                Panel::create()->header("Total Sales Per Customer")->type("info")->sub([
                    OutletDetailPieChart::create()
                    ->dataSource(
                        AutoMaker::table("customers")
                        ->join('outlets', 'outlets.outlet_id', 'customers.outlet_id')
                        ->leftJoin('transactions', 'transactions.customer_id', 'customers.customer_id')
                        ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
                        ->join('products', 'transaction_detail.product_id', 'products.id')
                        ->where('customers.outlet_id', $id)
                        ->select('products.productName as `Nama`', 'transaction_detail.qty as `Qty`')
                    ),
                ])->width(1/3),

                Panel::create()->header("Total Sales Per Distributor")->type("info")->sub([
                    OutletDetailTable2::create()
                    ->dataSource(
                        AutoMaker::table("outlets")
                        ->leftJoin('transactions', 'transactions.distributor_id', 'outlets.distributor_id')
                        ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
                        ->join('products', 'transaction_detail.product_id', 'products.id')
                        ->where('outlets.outlet_id', $id)
                        ->select('products.productName as `Nama`', 'outlets.outlet_city as `Kota`', 'transactions.grand_total as `Total Sale`', 'transactions.sale_return as `Total Sale Return`', 'transactions.due_payment as `Due Payment`')
                    ),
                ])->width(2/3),

                Panel::create()->header("Total Sales Per Distributor")->type("info")->sub([
                    OutletDetailPieChart2::create()
                    ->dataSource(
                        AutoMaker::table("outlets")
                        ->leftJoin('transactions', 'transactions.distributor_id', 'outlets.distributor_id')
                        ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
                        ->join('products', 'transaction_detail.product_id', 'products.id')
                        ->where('outlets.outlet_id', $id)
                        ->select('products.productName as `Nama`', 'transaction_detail.qty as `Qty`')
                    ),
                ])->width(1/3),

                // Panel::create()->header("List Customer")->type("info")->sub([
                //     OutletDetailCustomerTable::create()
                //     ->dataSource(
                //         AutoMaker::table("customers")
                //         ->join('outlets', 'outlets.outlet_id', 'customers.outlet_id')
                //         ->where('customers.outlet_id', $id)
                //         ->select('customers.customer_id as `No`', 'customers.customer_name as `Nama`', 'customers.customer_city as `Kota`', 'customers.customer_address as `Alamat`', 'customers.customer_contact_no as `No Telp`', 'customers.customer_email as `Email`', 'customers.customer_taxable_company as `Nama Pengusaha Kena Pajak`', 'customers.customer_npwp_address as `Alamat NPWP`', 'customers.customer_npwp_no as `No NPWP`')
                //     )
                // ])->width(1),
            ];
        });
    }

    protected function query($query) {
      $query
      ->leftJoin('transactions', 'transactions.outlet_id', 'outlets.outlet_id')
      ->select("transactions.id", "SUM(transactions.grand_total) as grand_total", "SUM(transactions.sale_return) as sale_return", "SUM(transactions.due_payment) as due_payment")
      ->select('outlets.outlet_id', "outlets.outlet_name", 'outlet_city', 'outlet_address')
      ->groupBy('transactions.outlet_id');
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
    //         OutletSale::create()
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
            ID::create("ID Outlet")
                ->colName('outlet_id')
                ->showOnIndex(false),
            Text::create("Nama")
                ->colName('outlet_name')
                ->searchable(true)
                ->sortable(true),
            Text::create("Alamat")->colName("outlet_address")
                ->searchable(true)
                ->sortable(true),
            Text::create("Kota")
                ->colName("outlet_city")
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
                OutletSaleChart::create()
                    ->xlsxExportable(true)
                    ->csvExportable(true)
            ])
            ->width(1)
            ->header("Chart Total Sales By Outlets"),

            // Panel::success([
            //     OutletSaleLine::create()
            //         ->xlsxExportable(true)
            //         ->csvExportable(true)
            // ])
            // ->width(1/2)
            // ->header("Outlets Sales Last 30 Days"),

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
                                Client::widget("OutletSaleTable")->exportToPDF("Report Outlet Sales " . date('Y-m-d His'))
                            ),
                        "Excel Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("OutletSaleTable")->exportToXLSX("Report Outlet Sales " . date('Y-m-d His'))
                            ),
                        "CSV Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("OutletSaleTable")->exportToCSV("Report Outlet Sales " . date('Y-m-d His'))
                            ),
                    ]),
                ]),

                OutletSaleTable::create()
                    ->pdfExportable(true)
                    ->xlsxExportable(true)
                    ->csvExportable(true),
            ])->size("lg"),
        ];
    }


}
