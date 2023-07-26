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
    protected function onCreated()
    {
        $this->pageSize(10)
            ->tableHover(true)
            ->tableStriped(true)
            ->pdfExportable(true)    //Allow exporting dashboard to PDF
            ->jpgExportable(true)    //Allow exporting dashboard to JPG
            ->pngExportable(true)    //Allow exporting dashboard to PNG
            ->xlsxExportable(true)   //Allow exporting dashboard to XLSX
            ->csvExportable(true)
            ->searchable(true)
            ->searchAlign("right")
            ->searchWidth("300px");
    }

    protected function onExporting($params)
    {
        if($params["all"]===true) {
            $this->pageSize(null);
        }
        return true;
    }

    public function exportedView()
    {
        return  Html::div([
                    Html::h1("Order List")
                ])->class("text-center")->view().
                $this->view();
    }

    protected function dataSource()
    {
        return AutoMaker::table("transactions")
            ->join('users', 'transactions.user_id', 'users.id')
            ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
            ->join('products', 'transaction_detail.product_id', 'products.id')
            ->select('transactions.*', 'users.name','transaction_detail.qty', 'transaction_detail.price', 'products.name as product_name', 'products.category', 'products.unit');

    }

    protected function fields()
    {
        return [
            Date::create("Tanggal Invoice")
                ->colName('invoice_date')
                ->searchable(true)
                ->sortable(true),
            Text::create("Nama Customer")
                ->colName('name')
                ->searchable(true)
                ->sortable(true),
            Text::create("No Invoice")
                ->colName('invoice_no')
                ->searchable(true)
                ->sortable(true),
            Text::create('SKU')
                ->colName("sku")
                ->searchable(true)
                ->sortable(true),
            Text::create("Nama Produk")
                ->colName('product_name')
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
                ->searchable(true)
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
}
