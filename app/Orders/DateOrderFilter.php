<?php
namespace App\Orders;

use \koolreport\dashboard\admin\filters\DateRangeFilter;
use App\AutoMaker;

class DateOrderFilter extends DateRangeFilter
{
    protected function onCreated()
    {
        $this->title("Tanggal Invoice");
    }

    protected function apply($query, $value)
    {
      //Return condition-applied query
      $startTime = isset($value[0]) ? $value[0] : date('Y-m-d 00:00:00');
      $endTime = isset($value[1]) ? $value[1] : date('Y-m-d 23:59:59');
      $startDate = date('Y-m-d', strtotime($startTime));
      $endDate = date('Y-m-d', strtotime($endTime));
      return $query->where("invoice_date", '>=', $startDate)
                ->where("invoice_date", '<=', $endDate);
    }
}
