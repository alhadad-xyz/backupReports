<?php

namespace App\Customers;

use \koolreport\dashboard\Client;

use \koolreport\dashboard\admin\Resource;

use \koolreport\dashboard\admin\actions\DeleteAction;
use \koolreport\dashboard\admin\actions\DetailAction;
use \koolreport\dashboard\admin\actions\InlineEditAction;
use \koolreport\dashboard\admin\actions\UpdateAction;

use \koolreport\dashboard\admin\relations\HasMany;

use \koolreport\dashboard\fields\ID;
use \koolreport\dashboard\fields\RelationLink;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Calculated;

use \koolreport\dashboard\containers\Modal;
use \koolreport\dashboard\containers\Inline;
use \koolreport\dashboard\containers\Panel;
use \koolreport\dashboard\containers\Row;

use \koolreport\dashboard\inputs\Button;
use \koolreport\dashboard\inputs\Dropdown;
use \koolreport\dashboard\inputs\Select;
use \koolreport\dashboard\widgets\KWidget;

use \koolreport\dashboard\menu\MenuItem;

use App\AutoMaker;
use App\Orders\OrderResource;
use App\Outlets\OutletResource;

class CustomerResource extends Resource
{
    protected function onCreated()
    {
        $this->manageTable("customers")->inSource(AutoMaker::class);

        //Allow searchBox
        $this->listScreen()->searchBox()
            ->enabled(true)
            ->placeHolder("Search Customers");

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
            return $data["customer_name"];
        });

        $this->detailScreen()->bottom(function($id){
            //$id is the id value of record that shown by detail screen
            $thirty_days_ago = date('Y-m-d', strtotime("-31 days"));
            return  [
                Row::create()->sub([
                    Panel::create()->header("Sales Last 30 Days")->type("info")->sub([
                        CustomerDetailLineChart::create()
                        ->dataSource(
                            AutoMaker::table("customers")
                            ->leftJoin('transactions', 'transactions.customer_id', 'customers.customer_id')
                            ->where('transactions.customer_id', $id)
                            ->where('invoice_date', '>=', $thirty_days_ago)
                            ->select("invoice_date", "COUNT('transactions.id') AS `Total Sales`")
                            ->groupBy('invoice_date')
                        ),
                    ])
                ]),

                Row::create()->sub([
                    Panel::create()->header("Sales Current Financial Year")->type("info")->sub([
                        CustomerDetailLineChart2::create()
                        ->dataSource(
                            AutoMaker::table("customers")
                            ->leftJoin('transactions', 'transactions.customer_id', 'customers.customer_id')
                            ->where('transactions.customer_id', $id)
                            ->select('MONTHNAME(invoice_date) as Month', 'COUNT(transactions.id) as `Total Sales`')
                            ->groupBy('Month')
                        ),
                    ])
                ]),
            ];
        });
    }

    protected function query($query) {
        $query->leftJoin('outlets', 'outlets.outlet_id', 'customers.outlet_id')
        ->select('customers.customer_id', 'customers.outlet_id', 'customers.customer_name', 'customers.customer_city', 'customers.customer_address', 'customers.customer_contact_no', 'customers.customer_email', 'customers.customer_taxable_company', 'customers.customer_npwp_address', 'customers.customer_npwp_no')
        ->select('outlet_name');
        return $query;
    }

    protected function filters()
    {
        return [
            OutletFilter::create()->title("Outlet"),
            CityFilter::create()->title("Kota"),
            CountryFilter::create()->title("Negara"),
        ];
    }

    protected function actions()
    {
        return [
            DetailAction::create()->showOnTable(true),
            UpdateAction::create()->showOnTable(true),
            InlineEditAction::create()->showOnTable(false),
            DeleteAction::create()->showOnTable(true),
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
            ID::create("ID Customer")
                ->colName('customer_id')
                ->showOnIndex(false),
            Text::create("Nama")
                ->colName('customer_name')
                ->searchable(true)
                ->sortable(true),
            RelationLink::create("outlet_id")
                ->label("Distributor")
                ->formatUsing(function($value,$row){
                    return $row["outlet_name"];
                })
                ->linkTo(OutletResource::class)
                ->inputWidget(
                    Select::create()
                    ->dataSource(function(){
                        return AutoMaker::table("outlets")
                                ->select("outlet_id","outlet_name");
                    })
                    ->fields(function(){
                        return [
                            ID::create("outlet_id"),
                            Text::create("outlet_name"),
                        ];
                    })
                ),
            Text::create("Kota")
                ->colName("customer_city")
                ->searchable(true)
                ->sortable(true),
            Text::create("Alamat")->colName("customer_address")
                ->searchable(true)
                ->sortable(true),
            Text::create("No Telp")->colName("customer_contact_no")
                ->searchable(true)
                ->sortable(true),
            Text::create("Email")->colName("customer_email")
                ->searchable(true)
                ->sortable(true),
            Text::create("Nama Pengusaha Kena Pajak")->colName("customer_taxable_company")
                ->searchable(true)
                ->sortable(true),
            Text::create("Alamat NPWP")->colName("customer_npwp_address")
                ->searchable(true)
                ->sortable(true),
            Text::create("No NPWP")->colName("customer_npwp_no")
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
                                Client::widget("CustomerTable")->exportToPDF("Report Customers " . date('Y-m-d His'))
                            ),
                        "Excel Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("CustomerTable")->exportToXLSX("Report Customers " . date('Y-m-d His'))
                            ),
                        "CSV Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("CustomerTable")->exportToCSV("Report Customers " . date('Y-m-d His'))
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
