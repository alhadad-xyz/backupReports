<?php
namespace App\BestSellerProducts;

use \koolreport\dashboard\admin\filters\Select2Filter;
use App\AutoMaker;

class CategoryFilter extends Select2Filter
{
    protected function onCreated()
    {
        $this->title("Category");
    }

    protected function apply($query, $value)
    {
      //Return condition-applied query
      if(isset($value)) {
        return $query->where("category", $value);
      } else {
        return $query;
      }
    }

    protected function options()
    {
        //Since this is Select2Filter so you have options() method
        //to provide list of options for Select
        //In here we list all available country from customers table
        return AutoMaker::table("products")
            ->select("category")
            ->distinct();
    }
}
