<?php
namespace App\BestSellerProducts;

use \koolreport\dashboard\Client;
use \koolreport\dashboard\Dashboard;
use \koolreport\dashboard\containers\Html;
use \koolreport\dashboard\containers\Panel;
use \koolreport\dashboard\containers\Row;
use \koolreport\dashboard\inputs\Dropdown;
use \koolreport\dashboard\menu\MenuItem;

class BestSellerProductReport extends Dashboard
{

    protected function onCreated()
    {
        $this->pdfExportable(true);
    }

    protected function content()
    {
        return [
            Html::h2("Best Seller Products"),

            // Panel::create()->header("Laporan Chart BestSellerProduct")->type("danger")->sub([
            //     BestSellerProductChart::create(),
            // ]),

            Panel::create()->header("Laporan Table BestSellerProduct")->type("primary")->sub([
                BestSellerProductTable::create()
            ]),
        ];
    }
}
