<?php

namespace App\Orders;

use \koolreport\dashboard\admin\actions\DeleteAction;
use \koolreport\dashboard\admin\actions\DetailAction;
use \koolreport\dashboard\admin\actions\InlineEditAction;
use \koolreport\dashboard\admin\actions\UpdateAction;
use \koolreport\dashboard\admin\relations\HasMany;
use \koolreport\dashboard\admin\Resource;
use \koolreport\dashboard\fields\Currency;
use \koolreport\dashboard\fields\ID;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Date;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\inputs\Select;
use \koolreport\dashboard\validators\NumericValidator;
use \koolreport\dashboard\Client;
use \koolreport\dashboard\containers\Html;
use \koolreport\dashboard\containers\Panel;
use \koolreport\dashboard\containers\Row;
use \koolreport\dashboard\inputs\Dropdown;
use \koolreport\dashboard\menu\MenuItem;
use \koolreport\dashboard\validators\RequiredFieldValidator;

use App\AutoMaker;
use App\Orders\Order;
use App\Orders\OrderChart;
use App\Orders\OrderTable;

class OrderResource extends Resource
{
    protected function onCreated()
    {
        $this->manageTable("transactions")->inSource(AutoMaker::class);

        //Allow searchBox
        $this->listScreen()->searchBox()
            ->enabled(true)
            ->placeHolder("Search Orders");

        $this->listScreen()->glassBox()
            ->type("primary");

    }

    protected function query($query) {
        $query->join('users', 'transactions.user_id', 'users.id')
        ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
        ->join('products', 'transaction_detail.product_id', 'products.id')
        ->select("transactions.id","invoice_date","invoice_no","discount","dpp","ppn","grand_total")
        ->select("transaction_detail.qty", "transaction_detail.price")
        ->select("users.name", "users.city")
        ->select("products.sku", "productName", "products.category", "products.unit");
        return $query;
    }

    // protected function relations()
    // {
    //     return [
    //         HasMany::resource(OrderDetail::class)
    //             ->link(["orderNumber"=>"orderNumber"])
    //     ];
    // }

    protected function filters()
    {
        return [
            TypeFilter::create()->title("Type"),
        ];
    }

    // protected function glasses()
    // {
    //     return [
    //         Order::create()
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
            Date::create("Tanggal Invoice")
                ->colName('invoice_date')
                ->searchable(true)
                ->sortable(true),
            Text::create("Nama Customer")
                ->colName('name')
                ->searchable(true)
                ->sortable(true),
            Text::create("Area Distributor")
                ->colName('city')
                ->searchable(true)
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
                ->colName("transaction_detail.price")
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
