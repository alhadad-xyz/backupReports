<?php
namespace App\Orders;

use \koolreport\dashboard\widgets\Table;
use \koolreport\dashboard\fields\Date;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\fields\Currency;
use \koolreport\dashboard\containers\Html;
use App\AutoMaker;

class OrderTable extends Table
{
    protected function onInit()
    {
        $this->pageSize(10);
    }

    protected function onExporting($params)
    {
        //Remove table paging when exporting to PDF
        $this->pageSize(null);
        return true;
    }

    public function exportedView()
    {
        return  Html::div([
                    Html::h1("Order Histories")
                ])->class("text-center")->view().
                $this->view();
    }

    protected function dataSource()
    {
        return AutoMaker::table("transactions")
            ->leftJoin('distributors', 'transactions.distributor_id', 'distributors.distributor_id')
            ->leftJoin('outlets', 'transactions.outlet_id', 'outlets.outlet_id')
            ->leftJoin('customers', 'transactions.customer_id', 'customers.customer_id')
            ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
            ->join('products', 'transaction_detail.product_id', 'products.id')
            ->select("transactions.id","invoice_date","invoice_no","discount","dpp","ppn","grand_total")
            ->select("transaction_detail.qty", "transaction_detail.price")
            ->select("COALESCE(distributors.distributor_name, outlets.outlet_name, customers.customer_name) AS name", "COALESCE(distributors.distributor_city, outlets.outlet_city, customers.customer_city)")
            ->select("products.sku", "productName", "products.category", "products.unit")
            ->orderBy('invoice_date', 'DESC');

    }

    protected function fields()
    {
        return [
            Date::create('invoice_date')
                ->label("Tanggal Invoice")
                ->searchable(true)
                ->sortable(true),
            Text::create('name')
                ->label("Nama Distributor")
                ->searchable(true)
                ->sortable(true),
            Text::create('invoice_no')
                ->label("No Invoice")
                ->searchable(true)
                ->sortable(true),
            Text::create('sku')
                ->label("SKU")
                ->searchable(true)
                ->sortable(true),
            Text::create('productName')
                ->label("Nama Produk")
                ->searchable(true)
                ->sortable(true),
            Number::create('qty')
                ->label("Qty")
                ->searchable(true)
                ->sortable(true),
            Text::create('category')
                ->label("Kategori")
                ->searchable(true)
                ->sortable(true),
            Text::create('unit')
                ->label("Satuan")
                ->searchable(true)
                ->sortable(true),
            Currency::create("price")
                ->label("Harga Satuan")
                ->IDR()
                ->symbol()
                ->searchable(true)
                ->sortable(true),
            Currency::create("discount")
                ->label("Discount")
                ->IDR()
                ->symbol()
                ->searchable(true)
                ->sortable(true),
            Currency::create("dpp")
                ->IDR()
                ->symbol(),
            Currency::create("ppn")
                ->IDR()
                ->symbol(),
            Currency::create("grand_total")
                ->IDR()
                ->symbol(),
        ];
    }
}
