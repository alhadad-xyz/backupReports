<?php
namespace App;

use \koolreport\dashboard\sources\MySQL;

class AutoMaker extends MySQL
{
    protected function connection()
    {
        return [
            "connectionString"=>"mysql:host=". env('DB_HOST', 'localhost') .";dbname=". env('DB_DATABASE', 'laravel'),
            "username"=> env('DB_USERNAME', 'root'),
            "password"=> env('DB_PASSWORD', ''),
            "charset"=> "utf8"
        ];
    }
}

