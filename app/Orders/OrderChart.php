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
            ->join('distributors', 'transactions.distributor_id', 'distributors.distributor_id')
            ->join('outlets', 'distributors.distributor_id', 'outlets.distributor_id')
            ->join('customers', 'distributors.outlet_id', 'customers.outlet_id')
            ->select('distributor_city', 'COUNT(distributor_city) as total_city')
            ->groupBy('distributor_city');
    }

    protected function fields()
    {
        return [
            Text::create("Kota")->colName('distributor_city'),
            Number::create("Total Order")->colName('total_city'),
        ];
    }
}
