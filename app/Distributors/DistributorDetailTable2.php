<?php

namespace App\Distributors;

use \koolreport\dashboard\widgets\KWidget;

class DistributorDetailTable2 extends KWidget
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
