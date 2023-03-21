<?php

namespace App\Outlets;

use \koolreport\dashboard\widgets\KWidget;

class OutletDetailTable2 extends KWidget
{
    protected function onInit()
    {
        $this
            ->use(\koolreport\datagrid\DataTables::class)
            ->settings([
                "isStacked"=>true
            ]);
    }
}
