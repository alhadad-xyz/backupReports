<?php
namespace App\Distributors;

use \koolreport\dashboard\Client;
use \koolreport\dashboard\Dashboard;
use \koolreport\dashboard\containers\Html;
use \koolreport\dashboard\containers\Panel;
use \koolreport\dashboard\containers\Row;
use \koolreport\dashboard\inputs\Dropdown;
use \koolreport\dashboard\menu\MenuItem;

class DistributorReport extends Dashboard
{
    protected function content()
    {
        return [
            Html::h2("Distributors"),

            Panel::create()->header("Laporan Chart Distributor")->type("danger")->sub([
                DistributorChart::create(),
            ]),

            Panel::create()->header("Laporan Table Distributor")->type("primary")->sub([
                Dropdown::create("exportOptions")
                ->title("<i class='far fa-file-pdf'></i> Export To PDF")
                ->items([
                    "Export Current Page"=>MenuItem::create()
                        ->onClick(
                            Client::showLoader().
                            Client::widget("DistributorTable")->exportToPDF("Distributors - Current Page",["all"=>false])
                        ),
                    "Export All"=>MenuItem::create()
                        ->onClick(
                            Client::showLoader().
                            Client::widget("DistributorTable")->exportToPDF("All Distributors",["all"=>true])
                        ),
                ])
                ->align("right")
                ->cssStyle("margin-bottom:5px;")
                ->cssClass("text-right"),
                DistributorTable::create()
            ]),
        ];
    }
}
