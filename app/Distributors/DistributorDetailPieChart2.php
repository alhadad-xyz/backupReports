<?php

namespace App\Distributors;

use \koolreport\dashboard\widgets\KWidget;

class DistributorDetailPieChart2 extends KWidget
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
