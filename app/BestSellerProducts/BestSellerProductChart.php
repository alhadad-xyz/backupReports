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
            ->leftJoin('distributors', 'transactions.distributor_id', 'distributors.distributor_id')
            ->leftJoin('outlets', 'transactions.outlet_id', 'outlets.outlet_id')
            ->leftJoin('customers', 'transactions.customer_id', 'customers.customer_id')
            ->join('transaction_detail', 'transactions.id', 'transaction_detail.transaction_id')
            ->join('products', 'transaction_detail.product_id', 'products.id')
            ->select('productName', 'SUM(transaction_detail.qty) as qty')
            ->groupBy('products.id');
    }

    protected function fields()
    {
        return [
            Text::create("Nama Produk")->colName('productName'),
            Number::create("Qty")->colName('qty'),
        ];
    }
}
