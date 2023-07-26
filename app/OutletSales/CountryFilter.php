<?php
namespace App\OutletSales;

use \koolreport\dashboard\admin\filters\Select2Filter;
use App\AutoMaker;

class CountryFilter extends Select2Filter
{
    protected function onCreated()
    {
        $this->title("country");
    }

    protected function apply($query, $value)
    {
      //Return condition-applied query
      if(isset($value)) {
        return $query->where("outlet_country", $value);
      } else {
        return $query;
      }
    }

    protected function options()
    {
        //Since this is Select2Filter so you have options() method
        //to provide list of options for Select
        //In here we list all available country from outlets table
        return AutoMaker::table("outlets")
        ->join('transactions', 'transactions.outlet_id', 'outlets.outlet_id')
        ->select("outlet_country")
        ->distinct();
    }
}
