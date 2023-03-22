<?php

namespace App\Customers;

use \koolreport\dashboard\widgets\google\ColumnChart;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\fields\Calculated;
use App\AutoMaker;

class CustomerChart extends ColumnChart
{
    protected function onInit()
    {
        $this->title("Kota")
            //->updateEffect("none")
            ->height("240px");
    }
    protected function dataSource()
    {
        //Get value from the date range picker
        // $range = $this->sibling("PaymentDateRange")->value();

        //Apply to query
        return AutoMaker::table("customers")
        ->select('customer_city', 'COUNT(customer_city) as total_city')
        ->groupBy('customer_city');
    }

    protected function fields()
    {
        return [
            Text::create("Kota")->colName('customer_city'),
            Number::create("Total Customer")->colName('total_city'),
        ];
    }
}
