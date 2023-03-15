<?php

namespace App;

use \koolreport\dashboard\sources\MySQL;

class AdminAutoMaker extends MySQL
{
    protected function connection()
    {
        /**
         * Local database connection sample
         */
        // return [
        //     "connectionString"=>"mysql:host=localhost;dbname=automaker",
        //     "username"=>"root",
        //     "password"=>"",
        //     "charset"=>"utf8"
        // ];

        /**
         * Note: We use public sample database of KoolReport so it will work but
         * a little slow. To get the better experience of Dashboard demo, please
         * install the automaker database into your local mysql database and
         * provide connection
         */
        return [
            "connectionString"=>"mysql:host=". env('DB_HOST', '103.180.165.204') .";port=". env('DB_PORT', '103.180.165.204') .";dbname=". env('DB_DATABASE', 'hessen_db'),
            "username"=> env('DB_USERNAME', 'root'),
            "password"=> env('DB_PASSWORD', ''),
            "charset"=> "utf8"
        ];
    }
}
