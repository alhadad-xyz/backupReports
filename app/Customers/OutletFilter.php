<?php
namespace App\Customers;

use \koolreport\dashboard\admin\filters\SelectFilter;
use App\AutoMaker;

class OutletFilter extends SelectFilter
{
    protected function onCreated()
    {
        $this->title("outlet");
    }

    protected function apply($query, $value)
    {
        //Return condition-applied query
        return $query->where("outlet_name", $value);
    }

    protected function options()
    {
        //Since this is SelectFilter so you have options() method
        //to provide list of options for Select
        //In here we list all available outlet from customers table
        return AutoMaker::table("outlets")
            ->select("outlet_name")
            ->orderBy('outlet_name', 'ASC')
            ->distinct();
    }
}
