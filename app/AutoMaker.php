<?php
namespace App;

use \koolreport\dashboard\sources\MySQL;

class AutoMaker extends MySQL
{
    protected function connection()
    {
        return [
            "connectionString"=>"mysql:host=". env('DB_HOST', '103.180.165.204') .";port=". env('DB_PORT', '103.180.165.204') .";dbname=". env('DB_DATABASE', 'hessen_db'),
            "username"=> env('DB_USERNAME', 'eyesimple'),
            "password"=> env('DB_PASSWORD', 'Isen@097330'),
            "charset"=> "utf8"
        ];
    }
}

