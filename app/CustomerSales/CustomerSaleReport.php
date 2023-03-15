<?php
namespace App\Customers;

use \koolreport\dashboard\Dashboard;

use \koolreport\dashboard\containers\Row;
use \koolreport\dashboard\containers\Panel;
use \koolreport\dashboard\widgets\Text;
use \koolreport\dashboard\widgets\StateHolder;

use \koolreport\dashboard\inputs\Dropdown;
use \koolreport\dashboard\menu\MenuItem;
use \koolreport\dashboard\Client;
use \koolreport\dashboard\containers\Html;

class CustomerReport extends Dashboard
{

    protected function onCreated()
    {
        $this
            ->pdfExportable(true);
        }

    protected function content()
    {
        return [
            Html::h2("Customers"),

            Panel::create()->header("Laporan Chart Customer")->type("danger")->sub([
                CustomerChart::create(),
            ]),

            Panel::create()->header("PDF Export")->type("danger")->sub([
                Dropdown::create("exportOptions")
                ->title("<i class='far fa-file-pdf'></i> Export To PDF")
                ->items([
                    "Export Current Page"=>MenuItem::create()
                        ->onClick(
                            Client::showLoader().
                            Client::widget("CustomerTable")->exportToPDF("Products - Current Page",["all"=>false])
                        ),
                    "Export All"=>MenuItem::create()
                        ->onClick(
                            Client::showLoader().
                            Client::widget("CustomerTable")->exportToPDF("All Products",["all"=>true])
                        ),
                ])
                ->align("right")
                ->cssStyle("margin-bottom:5px;")
                ->cssClass("text-right"),
                CustomerTable::create()
            ]),
        ];
    }
}
