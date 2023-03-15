<?php
namespace App\Home;

use \koolreport\dashboard\Client;
use \koolreport\dashboard\Dashboard;
use \koolreport\dashboard\containers\Html;
use \koolreport\dashboard\containers\Panel;
use \koolreport\dashboard\containers\Row;
use \koolreport\dashboard\inputs\Dropdown;
use \koolreport\dashboard\menu\MenuItem;
use App\Customers\CustomerChart;
use App\Distributors\DistributorChart;
use App\DistributorSales\DistributorSaleLine;
use App\CustomerSales\CustomerSaleLine;
use App\Orders\OrderLine;
// use App\Distributors\DistributorChart;

class HomeBoard extends Dashboard
{
    protected function content()
    {
        return [
            Row::create()->sub([
                Panel::create()->header("Sales Current Financial Year")->type("info")->sub([
                    OrderLine::create()
                ])
            ]),

            Row::create()->sub([
                Panel::create()->header("Laporan Chart Customer")->type("info")->sub([
                    CustomerSaleLine::create()->width(1/2),
                ]),
                Panel::create()->header("Laporan Chart Distributor")->type("info")->sub([
                    DistributorSaleLine::create()->width(1/2),
                ]),
            ]),

            Row::create()->sub([
                Panel::create()->header("Laporan Chart Customer")->type("info")->sub([
                    CustomerChart::create()->width(1/2),
                ]),

                Panel::create()->header("Laporan Chart Distributor")->type("info")->sub([
                    DistributorChart::create()->width(1/2),
                ]),
            ])
        ];
    }
}
