<?php
namespace App\Orders;

use \koolreport\dashboard\admin\filters\SelectFilter;
use App\AutoMaker;

class TypeFilter extends SelectFilter
{
    protected function onCreated()
    {
        $this->title("Type");
    }

    protected function apply($query, $value)
    {
        //Return condition-applied query
        return $query->where("type", $value);
    }

    protected function options()
    {
        //Since this is SelectFilter so you have options() method
        //to provide list of options for Select
        //In here we list all available country from customers table
        return AutoMaker::table("users")
            ->select("type")
            ->distinct();
    }
}
