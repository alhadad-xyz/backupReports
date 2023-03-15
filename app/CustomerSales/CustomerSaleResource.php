<?php

namespace App\CustomerSales;

use \koolreport\dashboard\Client;

use \koolreport\dashboard\admin\Resource;

use \koolreport\dashboard\admin\actions\DeleteAction;
use \koolreport\dashboard\admin\actions\DetailAction;
use \koolreport\dashboard\admin\actions\InlineEditAction;
use \koolreport\dashboard\admin\actions\UpdateAction;

use \koolreport\dashboard\fields\ID;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Currency;

use \koolreport\dashboard\containers\Modal;
use \koolreport\dashboard\containers\Inline;
use \koolreport\dashboard\containers\Panel;
use \koolreport\dashboard\containers\Row;

use \koolreport\dashboard\inputs\Button;
use \koolreport\dashboard\inputs\Dropdown;
use \koolreport\dashboard\inputs\Select;

use \koolreport\dashboard\menu\MenuItem;

use App\AutoMaker;
use App\CustomerSales\CityFilter;
use App\CustomerSales\CountryFilter;
use App\CustomerSales\CustomerSaleTable;
use App\CustomerSales\CustomerSaleChart;
use App\CustomerSales\CustomerSaleLine;

class CustomerSaleResource extends Resource
{
    protected function onCreated()
    {
        $this->manageTable("users")->inSource(AutoMaker::class);

        //Allow searchBox
        $this->listScreen()->searchBox()
            ->enabled(true)
            ->placeHolder("Search ...");

        $this->listScreen()->glassBox()
            ->type("primary");

        $this->listScreen()->createButton()
            ->enabled(false);

        $this->listScreen()->adminTable()
            ->tableStriped(true);
    }

    protected function query($query) {
        $query
        ->join('transactions', 'transactions.user_id', 'users.id')
        ->where('type', 'customer')
        ->select("transactions.id", "SUM(transactions.grand_total) as grand_total", "SUM(transactions.sale_return) as sale_return", "SUM(transactions.due_payment) as due_payment")
        ->select("users.name", 'city', 'address')
        ->groupBy('users.id');
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
    //         CustomerSale::create()
    //     ];
    // }

    protected function actions()
    {
        return [
            DetailAction::create()->showOnTable(false),
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
                ->searchable(true)
                ->sortable(true),
            Text::create("Alamat")->colName("address")
                ->searchable(true)
                ->sortable(true),
            Text::create("Kota")
                ->colName("city")
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
                CustomerSaleChart::create()
                    ->xlsxExportable(true)
                    ->csvExportable(true)
            ])
            ->width(1/2)
            ->header("Chart Total Sales By Customers"),

            Panel::success([
                CustomerSaleLine::create()
                    ->xlsxExportable(true)
                    ->csvExportable(true)
            ])
            ->width(1/2)
            ->header("Customers Sales Last 30 Days"),

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
                                Client::widget("CustomerSaleTable")->exportToPDF()
                            ),
                        "Excel Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("CustomerSaleTable")->exportToXLSX()
                            ),
                        "CSV Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("CustomerSaleTable")->exportToCSV()
                            ),
                    ]),
                ]),

                CustomerSaleTable::create()
                    ->pdfExportable(true)
                    ->xlsxExportable(true)
                    ->csvExportable(true),
            ])->size("lg"),
        ];
    }


}
