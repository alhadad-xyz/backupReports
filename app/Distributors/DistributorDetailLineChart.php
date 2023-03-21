<?php

namespace App\Distributors;

use \koolreport\dashboard\widgets\KWidget;

class DistributorDetailLineChart extends KWidget
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
