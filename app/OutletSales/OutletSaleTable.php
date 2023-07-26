<?php
namespace App\OutletSales;

use \koolreport\dashboard\widgets\Table;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\fields\Currency;
use \koolreport\dashboard\containers\Html;
use App\AutoMaker;

class OutletSaleTable extends Table
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
                    Html::h1("Outlet List")
                ])->class("text-center")->view().
                $this->view();
    }

    protected function dataSource()
    {
        return AutoMaker::table("outlets")
        ->leftJoin('transactions', 'transactions.outlet_id', 'outlets.outlet_id')
        ->select("transactions.id", "SUM(transactions.grand_total) as grand_total", "SUM(transactions.sale_return) as sale_return", "SUM(transactions.due_payment) as due_payment")
        ->select("outlet_name", 'outlet_city', 'outlet_address')
        ->groupBy('transactions.outlet_id');
    }

    protected function fields()
    {
        return [
            Text::create('outlet_name')
                ->label("Nama")
                ->searchable(true)
                ->sortable(true),
            Text::create("outlet_address")
                ->label("Alamat")
                ->searchable(true)
                ->sortable(true),
            Text::create("outlet_city")
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
