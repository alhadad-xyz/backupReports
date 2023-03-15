<?php
namespace App\DistributorSales;

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
        return $query->where("city", $value);
    }

    protected function options()
    {
        //Since this is SelectFilter so you have options() method
        //to provide list of options for Select
        //In here we list all available country from customers table
        return AutoMaker::table("users")
            ->join('transactions', 'transactions.user_id', 'users.id')
            ->where('type', 'distributor')
            ->select("city")
            ->distinct();
    }
}
