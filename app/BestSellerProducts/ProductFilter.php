<?php
namespace App\BestSellerProducts;

use \koolreport\dashboard\admin\filters\SelectFilter;
use App\AutoMaker;

class ProductFilter extends SelectFilter
{
    protected function onCreated()
    {
        $this->title("Product");
    }

    protected function apply($query, $value)
    {
        //Return condition-applied query
        return $query->where("productName", $value);
    }

    protected function options()
    {
        //Since this is SelectFilter so you have options() method
        //to provide list of options for Select
        //In here we list all available country from customers table
        return AutoMaker::table("products")
            ->select("productName")
            ->distinct();
    }
}
