<?php
namespace App\Outlets;

use \koolreport\dashboard\admin\filters\Select2Filter;
use App\AutoMaker;

class DistributorFilter extends Select2Filter
{
    protected function onCreated()
    {
        $this->title("distributor");
    }

    protected function apply($query, $value)
    {
      //Return condition-applied query
      if(isset($value)) {
        return $query->where("distributor_name", $value);
      } else {
        return $query;
      }
    }

    protected function options()
    {
        //Since this is Select2Filter so you have options() method
        //to provide list of options for Select
        //In here we list all available distributor from customers table
        return AutoMaker::table("distributors")
            ->select("distributor_name")
            ->orderBy('distributor_name', 'ASC')
            ->distinct();
    }
}
