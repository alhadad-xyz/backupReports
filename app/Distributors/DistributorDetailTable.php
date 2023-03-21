<?php

namespace App\Distributors;

use \koolreport\dashboard\widgets\KWidget;

class DistributorDetailTable extends KWidget
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
