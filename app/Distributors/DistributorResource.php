<?php

namespace App\Distributors;

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

class DistributorResource extends Resource
{
    protected function onCreated()
    {
        $this->manageTable("users")->inSource(AutoMaker::class);

        //Allow searchBox
        $this->listScreen()->searchBox()
            ->enabled(true)
            ->placeHolder("Search distributors");

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
                Row::create()->sub([
                    Panel::create()->header("Sales Last 30 Days")->type("info")->sub([
                        DistributorDetailLineChart::create()
                        ->dataSource(
                            AutoMaker::table("users")
                            ->join('transactions', 'transactions.user_id', 'users.id')
                            ->where('users.id', $id)
                            ->where('type', 'distributor')
                            ->where('invoice_date', '>=', $thirty_days_ago)
                            ->select("invoice_date", "COUNT('users.id') AS `Total Sales`")
                            ->groupBy('invoice_date')
                        ),
                    ])
                ]),

                Row::create()->sub([
                    Panel::create()->header("Sales Current Financial Year")->type("info")->sub([
                        DistributorDetailLineChart2::create()
                        ->dataSource(
                            AutoMaker::table("users")
                            ->join('transactions', 'transactions.user_id', 'users.id')
                            ->where('users.id', $id)
                            ->where('type', 'distributor')
                            ->select('MONTHNAME(invoice_date) as Month', 'COUNT(transactions.id) as `Total Sales`')
                            ->groupBy('Month')
                        ),
                    ])
                ]),
            ];
        });
    }

    protected function relations()
    {
        return [
            HasMany::resource(OrderResource::class)->link(["user_id"=>"id"])->title("Orders")
        ];
    }

    protected function query($query) {
        $query->where('type', 'distributor');
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
    //         Distributor::create()
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
                DistributorChart::create()
                    ->xlsxExportable(true)
                    ->csvExportable(true)
            ])
            ->width(1)
            ->header("Chart List Distributors"),

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
                                Client::widget("DistributorTable")->exportToPDF()
                            ),
                        "Excel Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("DistributorTable")->exportToXLSX()
                            ),
                        "CSV Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("DistributorTable")->exportToCSV()
                            ),
                    ]),

                    Dropdown::create("importOptions")
                    ->title("<i class='far fa-file-pdf'></i> Import")
                    ->items([
                        "Excel Import"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->href("distributorImportFromExcel"),
                    ]),
                ]),

                DistributorTable::create()
                    ->pdfExportable(true)
                    ->xlsxExportable(true)
                    ->csvExportable(true),
            ])->size("lg"),
        ];
    }


}
