<?php

namespace App\Customers;

use \koolreport\dashboard\Client;

use \koolreport\dashboard\admin\Resource;

use \koolreport\dashboard\admin\actions\DeleteAction;
use \koolreport\dashboard\admin\actions\DetailAction;
use \koolreport\dashboard\admin\actions\InlineEditAction;
use \koolreport\dashboard\admin\actions\UpdateAction;

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
use App\Customers\CityFilter;
use App\Customers\CountryFilter;
use App\Customers\Customer;
use App\Customers\CustomerChart;
use App\Customers\CustomerTable;

class CustomerResource extends Resource
{
    protected function onCreated()
    {
        $this->manageTable("users")->inSource(AutoMaker::class);

        //Allow searchBox
        $this->listScreen()->searchBox()
            ->enabled(true)
            ->placeHolder("Search Customers");

        $this->listScreen()->glassBox()
            ->type("primary");

        $this->listScreen()->createButton()
            ->enabled(false);

        $this->listScreen()->adminTable()
            ->tableStriped(true);
    }

    protected function query($query) {
        $query->where('type', 'customer');
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
    //         Customer::create()
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
            Text::create("Kota")
                ->colName("city")
                ->searchable(true)
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
                ->searchable(true)
                ->sortable(true),
            Text::create("No Telp")->colName("contact_no")
                ->searchable(true)
                ->sortable(true),
            Text::create("Email")->colName("email")
                ->searchable(true)
                ->sortable(true),
            Text::create("Nama Pengusaha Kena Pajak")->colName("taxable_company")
                ->searchable(true)
                ->sortable(true),
            Text::create("Alamat NPWP")->colName("npwp_address")
                ->searchable(true)
                ->sortable(true),
            Text::create("No NPWP")->colName("npwp_no")
                ->searchable(true)
                ->sortable(true),
        ];
    }

    protected function highlights()
    {
        return [
            Panel::success([
                CustomerChart::create()
                    ->xlsxExportable(true)
                    ->csvExportable(true)
            ])
            ->width(1)
            ->header("Chart List Customers"),

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
                                Client::widget("CustomerTable")->exportToPDF()
                            ),
                        "Excel Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("CustomerTable")->exportToXLSX()
                            ),
                        "CSV Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("CustomerTable")->exportToCSV()
                            ),
                    ]),

                    Dropdown::create("importOptions")
                    ->title("<i class='far fa-file-pdf'></i> Import")
                    ->items([
                        "Excel Import"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->href("customerImportFromExcel"),
                    ]),
                ]),

                CustomerTable::create()
                    ->pdfExportable(true)
                    ->xlsxExportable(true)
                    ->csvExportable(true),
            ])->size("lg"),
        ];
    }


}
