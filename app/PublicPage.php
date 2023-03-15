<?php

namespace App;

use \koolreport\dashboard\menu\Group;
use \koolreport\dashboard\menu\Section;
use \koolreport\dashboard\pages\Main;

use App\Customers\CustomerResource;
use App\Customers\CustomerReport;
use App\CustomerSales\CustomerSaleResource;
use App\Distributors\DistributorReport;
use App\Distributors\DistributorResource;
use App\DistributorSales\DistributorSaleResource;
use App\Orders\OrderReport;
use App\Orders\OrderResource;
use App\BestSellerProducts\BestSellerProductReport;
use App\BestSellerProducts\BestSellerProductResource;
use App\Home\HomeBoard;
use App\pdf\PDFBoard;

 class PublicPage extends Main
 {
    protected function onCreated()
    {
        $this->loginRequired(true); // Need login to access
    }
    protected function sidebar()
    {
        return [
            "Home"=>HomeBoard::create()->icon("fa fa-home"),
            "Users"=>Section::create()->sub([
                "Customers"=>CustomerResource::create()->icon("fas fa-users"),
                "Distributors"=>DistributorResource::create()->icon("fa fa-truck"),
            ]),

            "Report"=>Section::create()->sub([
                "Order History"=>OrderResource::create()->icon("fa fa-history"),
                "Best Seller Products"=>BestSellerProductResource::create()->icon("fa fa-chart-line"),
                "Sales Detail"=>Group::create()->icon("far fa-chart-bar")->sub([
                    "Total Sales By Customers"=>CustomerSaleResource::create()->icon("fas fa-users"),
                    "Total Sales By Distributors"=>DistributorSaleResource::create()->icon("fa fa-truck"),
                ]),
            ]),

            // "PDF Export"=>PDFBoard::create()->icon("fa fa-history"),
        ];
    }
}
