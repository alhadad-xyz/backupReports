<?php
namespace App\BestSellerProducts;

use \koolreport\dashboard\widgets\Table;
use \koolreport\dashboard\fields\Date;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\fields\Currency;
use \koolreport\dashboard\containers\Html;
use App\AutoMaker;

class BestSellerProductTable extends Table
{
    protected function onCreated()
    {
        $this->pageSize(10);
    }

    protected function onExporting($params)
    {
        // if(isset($params["all"])===true) {
        //     $this->pageSize(null);
        // }
        return true;
    }

    public function exportedView()
    {
        return  Html::div([
                    Html::h1("Product List")
                ])->class("text-center")->view().
                $this->view();
    }

    protected function dataSource()
    {
        return AutoMaker::table("transactions")
                ->join('users', 'transactions.user_id', 'users.id')
                ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
                ->join('products', 'transaction_detail.product_id', 'products.id')
                ->select("transactions.id","invoice_date","invoice_no","discount","dpp","ppn","grand_total")
                ->select("transaction_detail.qty", "transaction_detail.price")
                ->select("users.name", "users.city")
                ->select("productName", "products.category", "products.unit");

    }

    protected function fields()
    {
        return [
            Date::create('invoice_date')
                ->label("Tanggal Invoice")
                ->searchable(true)
                ->sortable(true),
            Text::create("city")
                ->label('Area Distributor')
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
