<?php

namespace App\Http\Controllers;

use App\App;
use App\Customers\Customer;
use Illuminate\Http\Request;
use \koolreport\dashboard\Dashboard;
use \koolreport\datagrid\DataTables;

use App\Imports\CustomersImport;
use App\Imports\DistributorsImport;
use App\Imports\OrdersImport;
use Maatwebsite\Excel\Facades\Excel;

class MyDashboardController extends Controller
{
    use \koolreport\laravel\Friendship;

    public function index() {
        $app = App::create()
        ->debugMode(true)
        ->run();
    }

    public function customerImportFromExcel() {
        return view('import.customers');
    }

    public function postCustomerImportFromExcel(Request $request) {
        try {
            Excel::import(new CustomersImport, $request->file('import_file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
       }
       return redirect('/');
    }

    public function distributorImportFromExcel() {
        return view('import.distributors');
    }

    public function postDistributorImportFromExcel(Request $request) {
        try {
            Excel::import(new DistributorsImport, $request->file('import_file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
        }
        return redirect('/');
    }

    public function orderImportFromExcel() {
        return view('import.orders');
    }

    public function postOrderImportFromExcel(Request $request) {
        try {
            Excel::import(new OrdersImport, $request->file('import_file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
        }
        return redirect('/');
    }
}
