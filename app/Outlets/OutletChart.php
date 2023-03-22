<?php

namespace App\Outlets;

use \koolreport\dashboard\widgets\google\ColumnChart;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\fields\Calculated;
use App\AutoMaker;

class OutletChart extends ColumnChart
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
        return AutoMaker::table("outlets")
            ->select('outlet_city', 'COUNT(outlet_city) as total_city')
            ->groupBy('outlet_city');
    }

    protected function fields()
    {
        return [
            Text::create("Kota")->colName('outlet_city'),
            Number::create("Total Outlet")->colName('total_city'),
        ];
    }
}
