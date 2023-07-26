<?php
namespace App\CustomerSales;

use \koolreport\dashboard\admin\filters\Select2Filter;
use App\AutoMaker;

class CityFilter extends Select2Filter
{
    protected function onCreated()
    {
        $this->title("city");
    }

    protected function apply($query, $value)
    {
      //Return condition-applied query
      if(isset($value)) {
        return $query->where("customer_city", $value);
      } else {
        return $query;
      }
    }

    protected function options()
    {
        //Since this is Select2Filter so you have options() method
        //to provide list of options for Select
        //In here we list all available country from customers table
        return AutoMaker::table("customers")
            ->join('transactions', 'transactions.customer_id', 'customers.customer_id')
            ->select("customer_city")
            ->distinct();
    }
}
