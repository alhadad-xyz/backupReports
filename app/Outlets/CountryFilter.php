<?php
namespace App\Outlets;

use \koolreport\dashboard\admin\filters\SelectFilter;
use App\AutoMaker;

class CountryFilter extends SelectFilter
{
    protected function onCreated()
    {
        $this->title("country");
    }

    protected function apply($query, $value)
    {
        //Return condition-applied query
        return $query->where("outlet_country", $value);
    }

    protected function options()
    {
        //Since this is SelectFilter so you have options() method
        //to provide list of options for Select
        //In here we list all available outlet_country from outlets table
        return AutoMaker::table("outlets")
        ->select("outlet_country")
        ->orderBy('outlet_country', 'ASC')
        ->distinct();
    }
}
