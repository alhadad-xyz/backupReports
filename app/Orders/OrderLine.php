<?php

namespace App\Orders;

use \koolreport\dashboard\widgets\d3\LineChart;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\fields\Calculated;
use \koolreport\dashboard\ColorList;
use App\AutoMaker;

class OrderLine extends LineChart
{
    protected function onInit()
    {
        $this
        ->colorScheme(ColorList::random())
        ->height("360px");
    }
    protected function dataSource()
    {
        //Get value from the date range picker
        // $range = $this->sibling("PaymentDateRange")->value();

        //Apply to query
        return AutoMaker::table("users")
            ->join('transactions', 'transactions.user_id', 'users.id')
            ->select('MONTHNAME(invoice_date) as month', 'COUNT(transactions.id) as total_sales')
            ->groupBy('month');
    }

    protected function fields()
    {
        return [
            Text::create("Bulan")->colName('month'),
            Number::create("Total Sales")->colName('total_sales'),
        ];
    }
}
