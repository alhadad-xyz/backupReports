<?php

namespace App\CustomerSales;

use \koolreport\dashboard\widgets\d3\LineChart;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\fields\Calculated;
use \koolreport\dashboard\ColorList;
use App\AutoMaker;

class CustomerSaleLine extends LineChart
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
        $thirty_days_ago = date('Y-m-d', strtotime("-31 days"));
        //Apply to query
        return AutoMaker::table("customers")
            ->leftJoin('transactions', 'transactions.customer_id', 'customers.customer_id')
            ->where('invoice_date', '>=', $thirty_days_ago)
            ->select("invoice_date", "COUNT('transactions.id') AS total_sales")
            ->groupBy('invoice_date');
    }

    protected function fields()
    {
        return [
            Text::create("invoice_date")->colName('invoice_date'),
            Number::create("Total Customer Sales")->colName('total_sales'),
        ];
    }
}
