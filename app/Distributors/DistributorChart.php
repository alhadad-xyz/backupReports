<?php

namespace App\Distributors;

use \koolreport\dashboard\widgets\google\ColumnChart;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\fields\Calculated;
use App\AutoMaker;

class DistributorChart extends ColumnChart
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
        return AutoMaker::table("distributors")
            ->select('distributor_city', 'COUNT(distributor_city) as total_city')
            ->groupBy('distributor_city')
            ->limit(10);
    }

    protected function fields()
    {
        return [
            Text::create("Kota")->colName('distributor_city'),
            Number::create("Total Distributor")->colName('total_city'),
        ];
    }
}
