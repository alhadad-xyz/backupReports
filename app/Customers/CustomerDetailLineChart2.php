<?php

namespace App\Customers;

use \koolreport\dashboard\widgets\KWidget;

class CustomerDetailLineChart2 extends KWidget
{
    protected function onInit()
    {
        $this
            ->use(\koolreport\d3\LineChart::class)
            ->settings([
                "isStacked"=>true
            ]);
    }

}
