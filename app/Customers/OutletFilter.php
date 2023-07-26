<?php
namespace App\Customers;

use \koolreport\dashboard\admin\filters\Select2Filter;
use App\AutoMaker;

class OutletFilter extends Select2Filter
{
    protected function onCreated()
    {
        $this->title("outlet");
    }

    protected function apply($query, $value)
    {
      //Return condition-applied query
      if(isset($value)) {
        return $query->where("outlet_name", $value);
      } else {
        return $query;
      }
    }

    protected function options()
    {
        //Since this is Select2Filter so you have options() method
        //to provide list of options for Select
        //In here we list all available outlet from customers table
        return AutoMaker::table("outlets")
            ->select("outlet_name")
            ->orderBy('outlet_name', 'ASC')
            ->distinct();
    }
}
