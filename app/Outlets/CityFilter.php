<?php
namespace App\Outlets;

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
        return $query->where("outlet_city", $value);
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
            ->select("outlet_city")
            ->orderBy('outlet_city', 'ASC')
            ->distinct();
    }
}
