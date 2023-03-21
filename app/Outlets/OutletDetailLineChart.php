<?php

namespace App\Outlets;

use \koolreport\dashboard\widgets\KWidget;

class OutletDetailLineChart extends KWidget
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
