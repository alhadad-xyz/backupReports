<?php

namespace App\Orders;

use \koolreport\dashboard\widgets\google\ColumnChart;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\fields\Calculated;
use App\AutoMaker;

class OrderChart extends ColumnChart
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
        return AutoMaker::table("transaction")
            ->select('city', 'COUNT(city) as total_city')
            ->groupBy('city');
    }

    protected function fields()
    {
        return [
            Text::create("Kota")->colName('city'),
            Number::create("Total Order")->colName('total_city'),
        ];
    }
}
