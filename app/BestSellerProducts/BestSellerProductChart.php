<?php

namespace App\BestSellerProducts;

use \koolreport\dashboard\widgets\google\PieChart;
use \koolreport\dashboard\fields\Text;
use \koolreport\dashboard\fields\Number;
use \koolreport\dashboard\fields\Calculated;
use \koolreport\dashboard\ColorList;
use App\AutoMaker;

class BestSellerProductChart extends PieChart
{
    protected function onInit()
    {

        $this->title("Best Seller Products")
        ->colorScheme(ColorList::random())
        ->height("240px");
    }

    protected function dataSource()
    {
        //Get value from the date range picker
        // $range = $this->sibling("PaymentDateRange")->value();

        //Apply to query
        return AutoMaker::table("transactions")
            ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
            ->join('products', 'transaction_detail.product_id', 'products.id')
            ->select('productName', 'transaction_detail.qty');
    }

    protected function fields()
    {
        return [
            Text::create("Nama Produk")->colName('productName'),
            Number::create("Qty")->colName('qty'),
        ];
    }
}
