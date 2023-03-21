<?php

namespace App\Outlets;

use \koolreport\dashboard\Client;

use \koolreport\dashboard\admin\Resource;

use \koolreport\dashboard\admin\actions\DeleteAction;
use \koolreport\dashboard\admin\actions\DetailAction;
use \koolreport\dashboard\admin\actions\InlineEditAction;
use \koolreport\dashboard\admin\actions\UpdateAction;

use \koolreport\dashboard\admin\relations\HasMany;

use \koolreport\dashboard\fields\ID;
use \koolreport\dashboard\fields\Text;

use \koolreport\dashboard\containers\Modal;
use \koolreport\dashboard\containers\Inline;
use \koolreport\dashboard\containers\Panel;
use \koolreport\dashboard\containers\Row;

use \koolreport\dashboard\inputs\Button;
use \koolreport\dashboard\inputs\Dropdown;
use \koolreport\dashboard\inputs\Select;

use \koolreport\dashboard\menu\MenuItem;

use App\AutoMaker;
use App\Orders\OrderResource;

class OutletResource extends Resource
{
    protected function onCreated()
    {
        $this->manageTable("users")->inSource(AutoMaker::class);

        //Allow searchBox
        $this->listScreen()->searchBox()
            ->enabled(true)
            ->placeHolder("Search Outlets");

        $this->listScreen()->glassBox()
            ->type("primary");

        $this->listScreen()->createButton()
            ->enabled(false);

        $this->listScreen()->adminTable()
            ->tableStriped(true);

        $this->detailScreen()->title(function(){
            $data = $this->data();
            return $data["name"];
        });


        $this->detailScreen()->highlights(function($id){
            //$id is the id value of record that shown by detail screen
            $thirty_days_ago = date('Y-m-d', strtotime("-31 days"));
            return  [
                Panel::create()->header("Sales Last 30 Days")->type("info")->sub([
                    OutletDetailLineChart::create()
                        ->dataSource(
                            AutoMaker::table("outlet_has_customers as ohc")
                            ->join('users as outlet', 'outlet.id', 'ohc.outlet_id')
                            ->join('users as customer', 'customer.id', 'ohc.customer_id')
                            ->leftJoin('transactions', 'transactions.user_id', 'outlet.id')
                            ->where('ohc.outlet_id', $id)
                            ->where('invoice_date', '>=', $thirty_days_ago)
                            ->select("invoice_date", "COUNT('transactions.id') AS `Total Sales`")
                            ->groupBy('invoice_date')
                        ),
                ])->width(1),

                Panel::create()->header("Sales Current Financial Year")->type("info")->sub([
                    OutletDetailLineChart2::create()
                    ->dataSource(
                        AutoMaker::table("outlet_has_customers as ohc")
                        ->join('users as outlet', 'outlet.id', 'ohc.outlet_id')
                        ->join('users as customer', 'customer.id', 'ohc.customer_id')
                        ->leftJoin('transactions', 'transactions.user_id', 'outlet.id')
                        ->where('ohc.outlet_id', $id)
                        ->select('MONTHNAME(invoice_date) as Month', 'COUNT(transactions.id) as `Total Sales`')
                        ->groupBy('Month')
                    ),
                ])->width(1),

                Panel::create()->header("Total Sales Per Customer")->type("info")->sub([
                    OutletDetailTable::create()
                    ->dataSource(
                        AutoMaker::table("outlet_has_customers as ohc")
                        ->join('users as outlet', 'outlet.id', 'ohc.outlet_id')
                        ->join('users as customer', 'customer.id', 'ohc.customer_id')
                        ->leftJoin('transactions', 'transactions.user_id', 'customer.id')
                        ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
                        ->join('products', 'transaction_detail.product_id', 'products.id')
                        ->where('ohc.outlet_id', $id)
                        ->select('products.productName as `Nama`', 'customer.address as `Alamat`', 'customer.city as `Kota`', 'transactions.grand_total as `Total Sale`', 'transactions.sale_return as `Total Sale Return`', 'transactions.due_payment as `Due Payment`')
                        // ->groupBy('outlet.id')
                    ),
                ])->width(2/3),

                Panel::create()->header("Total Sales Per Customer")->type("info")->sub([
                    OutletDetailPieChart::create()
                    ->dataSource(
                        AutoMaker::table("outlet_has_customers as ohc")
                        ->join('users as outlet', 'outlet.id', 'ohc.outlet_id')
                        ->join('users as customer', 'customer.id', 'ohc.customer_id')
                        ->leftJoin('transactions', 'transactions.user_id', 'customer.id')
                        ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
                        ->join('products', 'transaction_detail.product_id', 'products.id')
                        ->where('ohc.outlet_id', $id)
                        ->select('products.productName as `Nama`', 'transaction_detail.qty as `Qty`')
                    ),
                ])->width(1/3),

                Panel::create()->header("Total Sales Per Distributor")->type("info")->sub([
                    OutletDetailTable2::create()
                    ->dataSource(
                        AutoMaker::table("users as distributor")
                        ->leftJoin('transactions', 'transactions.user_id', 'distributor.id')
                        ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
                        ->join('products', 'transaction_detail.product_id', 'products.id')
                        ->where('distributor.id', $id)
                        ->select('products.productName as `Nama`', 'distributor.city as `Kota`', 'transactions.grand_total as `Total Sale`', 'transactions.sale_return as `Total Sale Return`', 'transactions.due_payment as `Due Payment`')
                        // ->groupBy('outlet.id')
                    ),
                ])->width(2/3),

                Panel::create()->header("Total Sales Per Distributor")->type("info")->sub([
                    OutletDetailPieChart2::create()
                    ->dataSource(
                        AutoMaker::table("users as distributor")
                        ->leftJoin('transactions', 'transactions.user_id', 'distributor.id')
                        ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
                        ->join('products', 'transaction_detail.product_id', 'products.id')
                        ->where('distributor.id', $id)
                        ->select('products.productName as `Nama`', 'transaction_detail.qty as `Qty`')
                    ),
                ])->width(1/3),

                Panel::create()->header("List Customer")->type("info")->sub([
                    OutletDetailCustomerTable::create()
                    ->dataSource(
                        AutoMaker::table("outlet_has_customers as ohc")
                        ->join('users as outlet', 'outlet.id', 'ohc.outlet_id')
                        ->join('users as customer', 'customer.id', 'ohc.customer_id')
                        ->where('ohc.outlet_id', $id)
                        ->select('customer.id as `No`', 'customer.name as `Nama`', 'customer.city as `Kota`', 'customer.address as `Alamat`', 'customer.contact_no as `No Telp`', 'customer.email as `Email`', 'customer.taxable_company as `Nama Pengusaha Kena Pajak`', 'customer.npwp_address as `Alamat NPWP`', 'customer.npwp_no as `No NPWP`')
                    ),
                ])->width(1),
            ];
        });
    }

    protected function relations()
    {
        return [
            // HasMany::resource(OrderResource::class)->link(["user_id"=>"id"])->title("Orders")
        ];
    }

    protected function query($query) {
        $query->leftJoin('distributor_has_outlets as dho', 'users.id', 'dho.outlet_id')
            ->leftJoin('users as distributor', 'distributor.id', 'dho.distributor_id')
            ->where('users.type', 'outlet')
            ->select('users.*', 'distributor.name as distributor');
        return $query;
    }

    protected function filters()
    {
        return [
            CityFilter::create()->title("Kota"),
            CountryFilter::create()->title("Negara"),
        ];
    }

    // protected function glasses()
    // {
    //     return [
    //         Outlet::create()
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
            ID::create("#")
                ->colName('id'),
            Text::create("Nama")
                ->colName('name')
                // ->searchable(true)
                ->sortable(true),
            Text::create("Distributor")
                ->colName('distributor')
                // ->searchable(true)
                ->sortable(true),
            Text::create("Kota")
                ->colName("city")
                // ->searchable(true)
                ->sortable(true)
                ->inputWidget(
                Select::create()
                ->dataSource(function(){
                    return AutoMaker::table("users")->select("city")->distinct()->orderBy("city");
                })
                ->fields(function(){
                    return [
                        Text::create("city")
                    ];
                })
            ),
            Text::create("Alamat")->colName("address")
                // ->searchable(true)
                ->sortable(true),
            Text::create("No Telp")->colName("contact_no")
                // ->searchable(true)
                ->sortable(true),
            Text::create("Email")->colName("email")
                // ->searchable(true)
                ->sortable(true),
            Text::create("Nama Pengusaha Kena Pajak")->colName("taxable_company")
                // ->searchable(true)
                ->sortable(true),
            Text::create("Alamat NPWP")->colName("npwp_address")
                // ->searchable(true)
                ->sortable(true),
            Text::create("No NPWP")->colName("npwp_no")
                // ->searchable(true)
                ->sortable(true),
        ];
    }

    protected function highlights()
    {
        return [
            Panel::success([
                OutletChart::create()
                    ->xlsxExportable(true)
                    ->csvExportable(true)
            ])
            ->width(1)
            ->header("Chart List Outlets"),

            Button::create()->text("Export & Import")->onClick(function(){
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
                                Client::widget("OutletTable")->exportToPDF()
                            ),
                        "Excel Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("OutletTable")->exportToXLSX()
                            ),
                        "CSV Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("OutletTable")->exportToCSV()
                            ),
                    ]),

                    Dropdown::create("importOptions")
                    ->title("<i class='far fa-file-pdf'></i> Import")
                    ->items([
                        "Excel Import"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->href("outletImportFromExcel"),
                    ]),
                ]),

                OutletTable::create()
                    ->pdfExportable(true)
                    ->xlsxExportable(true)
                    ->csvExportable(true),
            ])->size("lg"),
        ];
    }


}
