<?php

namespace App\Customers;

use \koolreport\dashboard\widgets\KWidget;

class CustomerDetailPieChart extends KWidget
{
    protected function onInit()
    {
        $this
            ->use(\koolreport\d3\PieChart::class)
            ->settings([
                "isStacked"=>true
            ]);
    }
}
