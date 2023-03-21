<?php
namespace App\Orders;

use \koolreport\dashboard\admin\filters\SelectFilter;
use App\AutoMaker;

class OutletFilter extends SelectFilter
{
    protected function onCreated()
    {
        $this->title("Outlet");
    }

    protected function apply($query, $value)
    {
        //Return condition-applied query
        return $query->where("name", $value);
    }

    protected function options()
    {
        //Since this is SelectFilter so you have options() method
        //to provide list of options for Select
        //In here we list all available country from customers table
        return AutoMaker::table("users")
            ->where('type', 'outlet')
            ->select("name")
            ->distinct();
    }
}
