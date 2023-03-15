<?php
namespace App\Orders;

use \koolreport\dashboard\Client;
use \koolreport\dashboard\Dashboard;
use \koolreport\dashboard\containers\Html;
use \koolreport\dashboard\containers\Panel;
use \koolreport\dashboard\containers\Row;
use \koolreport\dashboard\inputs\Dropdown;
use \koolreport\dashboard\menu\MenuItem;

class OrderReport extends Dashboard
{

    protected function onCreated()
    {
        $this->pdfExportable(true);
        }

    protected function content()
    {
        return [
            Html::h2("Orders"),

            // Panel::create()->header("Laporan Chart Order")->type("danger")->sub([
            //     OrderChart::create(),
            // ]),

            Panel::create()->header("Laporan Table Order")->type("primary")->sub([
                OrderTable::create()
            ]),
        ];
    }
}
