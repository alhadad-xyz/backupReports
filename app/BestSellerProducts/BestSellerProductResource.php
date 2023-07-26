<?php

namespace App\BestSellerProducts;

use \koolreport\dashboard\Client;

use \koolreport\dashboard\admin\Resource;

use \koolreport\dashboard\admin\actions\DeleteAction;
use \koolreport\dashboard\admin\actions\DetailAction;
use \koolreport\dashboard\admin\actions\InlineEditAction;
use \koolreport\dashboard\admin\actions\UpdateAction;

use \koolreport\dashboard\fields\ID;
use \koolreport\dashboard\fields\Currency;
use \koolreport\dashboard\fields\Date;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\fields\Text;
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
use App\BestSellerProducts\BestSellerProduct;
use App\BestSellerProducts\BestSellerProductChart;
use App\BestSellerProducts\BestSellerProductTable;

class BestSellerProductResource extends Resource
{
    protected function onCreated()
    {
        $this->manageTable("transactions")->inSource(AutoMaker::class);

        //Allow searchBox
        $this->listScreen()->searchBox()
            ->enabled(true)
            ->placeHolder("Search BestSellerProducts");

        $this->listScreen()->glassBox()
            ->type("primary");

        $this->listScreen()->createButton()
            ->enabled(false);
    
        $this->listScreen()->actionBox()
            ->enabled(false);

        $this->listScreen()->adminTable()
            ->tableStriped(true);

    }

    protected function query($query) {
        $query
        ->leftJoin('distributors', 'transactions.distributor_id', 'distributors.distributor_id')
        ->leftJoin('outlets', 'transactions.outlet_id', 'outlets.outlet_id')
        ->leftJoin('customers', 'transactions.customer_id', 'customers.customer_id')
        ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
        ->join('products', 'transaction_detail.product_id', 'products.id')
        ->select("transactions.id","invoice_date","invoice_no","SUM(discount) as discount","SUM(dpp) as dpp","SUM(ppn) as ppn","SUM(grand_total) as grand_total")
        ->select("SUM(transaction_detail.qty) as qty",)
        ->select("COALESCE(distributors.distributor_name, outlets.outlet_name, customers.customer_name) AS name", "COALESCE(distributors.distributor_city, outlets.outlet_city, customers.customer_city) AS city")
        ->select("products.sku", "productName", "products.category", "products.unit", "products.price")
        ->groupBy("products.id");
        return $query;
    }

    protected function filters()
    {
        return [
            DateOrderFilter::create()->title("Tanggal Invoice"),
            CustomerFilter::create()->title("Customer"),
            ProductFilter::create()->title("Product"),
            CategoryFilter::create()->title("Category"),
            AreaFilter::create()->title("Area"),
        ];
    }
    
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
            Calculated::create("#", function($row) {
                static $index = 0;
                $index++;
                return $index/2;
            }),
            ID::create("ID Order")
                ->colName('id')
                ->showOnIndex(false),
            Date::create("Tanggal Invoice")
                ->colName('invoice_date')
                ->searchable(true)
                ->sortable(true),
            Text::create("Area Distributor")
                ->colName('city')
                ->searchable(false)
                ->sortable(true),
            Text::create("No Invoice")
                ->colName('invoice_no')
                ->searchable(true)
                ->sortable(true),
            Text::create("SKU")
                ->colName('sku')
                ->searchable(true)
                ->sortable(true),
            Text::create("Nama Produk")
                ->colName('productName')
                ->searchable(true)
                ->sortable(true),
            Number::create("Qty")
                ->colName('qty')
                ->searchable(true)
                ->sortable(true),
            Text::create("Kategori")
                ->colName('category')
                ->searchable(true)
                ->sortable(true),
            Text::create("Satuan")
                ->colName('unit')
                ->searchable(true)
                ->sortable(true),
            Currency::create("Harga Satuan")->IDR()->symbol()
                ->colName("price")
                ->searchable(false)
                ->sortable(true),
            Currency::create("Discount")->IDR()->symbol()
                ->colName("discount")
                ->searchable(true)
                ->sortable(true),
            Currency::create("dpp")->IDR()->symbol(),
            Currency::create("ppn")->IDR()->symbol(),
            Currency::create("grand_total")->IDR()->symbol(),
        ];
    }

    protected function highlights()
    {
        return [
            Panel::success([
                BestSellerProductChart::create()
                    ->xlsxExportable(true)
                    ->csvExportable(true)
            ])
            ->width(1)
            ->header("Chart List Best Seller Products"),

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
                                Client::widget("BestSellerProductTable")->exportToPDF("Report Best Seller Products " . date('Y-m-d His'))
                            ),
                        "Excel Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("BestSellerProductTable")->exportToXLSX("Report Best Seller Products " . date('Y-m-d His'))
                            ),
                        "CSV Export"=>MenuItem::create()->icon("far fa-file-pdf")
                            ->onClick(
                                Client::showLoader().
                                Client::widget("BestSellerProductTable")->exportToCSV("Report Best Seller Products " . date('Y-m-d His'))
                            ),
                    ]),
                ]),

                BestSellerProductTable::create()
                    ->pdfExportable(true)
                    ->xlsxExportable(true)
                    ->csvExportable(true),
            ])->size("lg"),
        ];
    }

}
