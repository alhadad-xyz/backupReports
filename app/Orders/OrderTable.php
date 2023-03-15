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
            ->join('users', 'transactions.user_id', 'users.id')
            ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
            ->join('products', 'transaction_detail.product_id', 'products.id')
            ->select('transactions.*', 'users.name','transaction_detail.qty', 'transaction_detail.price', 'productName', 'products.category', 'products.unit')
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
                ->label("Nama Customer")
                ->searchable(true)
                ->sortable(true),
            Text::create('invoice_no')
                ->label("No Invoice")
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
