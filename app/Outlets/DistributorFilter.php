<?php
namespace App\Outlets;

use \koolreport\dashboard\admin\filters\SelectFilter;
use App\AutoMaker;

class DistributorFilter extends SelectFilter
{
    protected function onCreated()
    {
        $this->title("distributor");
    }

    protected function apply($query, $value)
    {
        //Return condition-applied query
        return $query->where("distributor_name", $value);
    }

    protected function options()
    {
        //Since this is SelectFilter so you have options() method
        //to provide list of options for Select
        //In here we list all available distributor from customers table
        return AutoMaker::table("distributors")
            ->select("distributor_name")
            ->orderBy('distributor_name', 'ASC')
            ->distinct();
    }
}
