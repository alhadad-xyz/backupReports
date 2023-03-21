<?php

namespace App\Outlets;

use \koolreport\dashboard\widgets\KWidget;

class OutletDetailPieChart extends KWidget
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
