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
        return $query->where("country", $value);
    }

    protected function options()
    {
        //Since this is SelectFilter so you have options() method
        //to provide list of options for Select
        //In here we list all available country from outlets table
        return AutoMaker::table("users")
            ->where('type', 'outlet')
            ->select("country")
            ->distinct();
    }
}
