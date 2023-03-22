<?php
namespace App\Distributors;

use \koolreport\dashboard\admin\filters\SelectFilter;
use App\AutoMaker;

class CityFilter extends SelectFilter
{
    protected function onCreated()
    {
        $this->title("city");
    }

    protected function apply($query, $value)
    {
        //Return condition-applied query
        return $query->where("distributor_city", $value);
    }

    protected function options()
    {
        //Since this is SelectFilter so you have options() method
        //to provide list of options for Select
        //In here we list all available country from customers table
        return AutoMaker::table("distributors")
            ->select("distributor_city")
            ->distinct();
    }
}
