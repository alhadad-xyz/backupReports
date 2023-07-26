<?php
namespace App\Orders;

use \koolreport\dashboard\admin\filters\DateRangeFilter;
use App\AutoMaker;

class DateOrderFilter extends DateRangeFilter
{
    protected function onCreated()
    {
        $this->title("Tanggal");
    }

    protected function apply($query, $value)
    {
        //Return condition-applied query
        return $query->whereBetween("invoice_date", [$startDate, $endDate]);
    }

    // protected function options()
    // {
    //     //Since this is SelectFilter so you have options() method
    //     //to provide list of options for Select
    //     //In here we list all available country from customers table
    //     return AutoMaker::table("customers")
    //         ->select("customer_name")
    //         ->distinct();
    // }
}
