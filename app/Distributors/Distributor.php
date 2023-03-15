<?php

namespace App\Distributors;

use koolreport\dashboard\admin\glasses\Glass;
use koolreport\dashboard\fields\Currency;
use koolreport\dashboard\fields\ID;
use koolreport\dashboard\fields\Text;

class Distributor extends Glass
{
    protected function onCreated()
    {
        $this->type("warning")
        ->icon("fa fa-building");
    }

    protected function query($query)
    {
        $query->where('type', 'distributor');
        return $query;
    }

    // protected function fields()
    // {
    //     return [
    //         ID::create("customerNumber")
    //             ->searchable(true)
    //             ->sortable(true),
    //         Text::create("customerName")
    //             ->searchable(true)
    //             ->sortable(true),
    //         Text::create("country")
    //             ->searchable(true)
    //             ->sortable(true),
    //         Currency::create("totalPayment")
    //             ->USD()->symbol()
    //     ];
    // }
}
