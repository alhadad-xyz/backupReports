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
use App\CustomerSales\CityFilter;
use App\CustomerSales\CountryFilter;
use App\CustomerSales\CustomerSaleTable;
use App\CustomerSales\CustomerSaleChart;
use App\CustomerSales\CustomerSaleLine;
use App\Customers\CustomerDetailLineChart;
use App\Customers\CustomerDetailLineChart2;
use App\Customers\CustomerDetailTable;
use App\Customers\CustomerDetailTable2;
use App\Customers\CustomerDetailPieChart;
use App\Customers\CustomerDetailPieChart2;

class CustomerSaleResource extends Resource
{
    protected function onCreated()
    {
        $this->manageTable("customers")->inSource(AutoMaker::class);

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
      $query
      ->leftJoin('transactions', 'transactions.customer_id', 'customers.customer_id')
      ->select("transactions.id", "SUM(transactions.grand_total) as grand_total", "SUM(transactions.sale_return) as sale_return", "SUM(transactions.due_payment) as due_payment")
      ->select('customers.customer_id', "customers.customer_name", 'customer_city', 'customer_address')
      ->groupBy('transactions.customer_id');
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
    //         CustomerSale::create()
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
            ID::create("ID Customer")
                ->colName('customer_id')
                ->showOnIndex(false),
            Text::create("Nama")
                ->colName('customer_name')
                ->searchable(true)
                ->sortable(true),
            Text::create("Alamat")->colName("customer_address")
                ->searchable(true)
                ->sortable(true),
            Text::create("Kota")
                ->colName("customer_city")
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
            ->width(1)
            ->header("Chart Total Sales By Customers"),

            // Panel::success([
            //     CustomerSaleLine::create()
            //         ->xlsxExportable(true)
            //         ->csvExportable(true)
            // ])
            // ->width(1/2)
            // ->header("Customers Sales Last 30 Days"),

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
                                Client::widget("CustomerSaleTable")->exportToPDF("Report Customer Sales " . date('Y-m-d His'))
                            ),
                        "Excel Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("CustomerSaleTable")->exportToXLSX("Report Customer Sales " . date('Y-m-d His'))
                            ),
                        "CSV Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("CustomerSaleTable")->exportToCSV("Report Customer Sales " . date('Y-m-d His'))
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
