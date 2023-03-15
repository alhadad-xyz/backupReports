<?php
namespace App\CustomerSales;

use \koolreport\dashboard\widgets\Table;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\fields\Currency;
use \koolreport\dashboard\containers\Html;
use App\AutoMaker;

class CustomerSaleTable extends Table
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
                    Html::h1("Customer List")
                ])->class("text-center")->view().
                $this->view();
    }

    protected function dataSource()
    {
        return AutoMaker::table("users")
        ->join('transactions', 'transactions.user_id', 'users.id')
        ->where('type', 'customer')
        ->select("transactions.id", "SUM(transactions.grand_total) as grand_total", "SUM(transactions.sale_return) as sale_return", "SUM(transactions.due_payment) as due_payment")
        ->select("users.name", 'city', 'address')
        ->groupBy('users.id');
    }

    protected function fields()
    {
        return [
            Text::create('name')
                ->label("Nama")
                ->searchable(true)
                ->sortable(true),
            Text::create("address")
                ->label("Alamat")
                ->searchable(true)
                ->sortable(true),
            Text::create("city")
                ->label("Kota")
                ->searchable(true)
                ->sortable(true),
            Currency::create("grand_total")
                ->label("Total Sale")->IDR()->symbol()
                ->searchable(true)
                ->sortable(true),
            Currency::create("sale_return")
                ->label("Total Sale Return")->IDR()->symbol()
                ->searchable(true)
                ->sortable(true),
            Currency::create("due_payment")
                ->label("Due Payment")->IDR()->symbol()
                ->searchable(true)
                ->sortable(true),
        ];
    }
}
