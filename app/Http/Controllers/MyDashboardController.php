<?php

namespace App\Http\Controllers;

use App\App;
use App\Customers\Customer;
use Illuminate\Http\Request;
use \koolreport\dashboard\Dashboard;
use \koolreport\datagrid\DataTables;

use App\Imports\CustomersImport;
use App\Imports\DistributorsImport;
use App\Imports\OutletsImport;
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
            $request->validate([
              'import_file' => 'required|mimes:xlsx,xlx,xls'
            ],[
              'import_file.required' => 'File is required!'
            ]);

            Excel::import(new CustomersImport, $request->file('import_file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                return redirect()->back()->withErrors(['msg' => $failure->errors()[0] . ' at row ' . $failure->row()]);
            }
       }
       return redirect()->to('https://hessen.eyesimple.us/?kdr=eyJyb3V0ZSI6IkFwcC9QdWJsaWNQYWdlL0N1c3RvbWVyUmVzb3VyY2UiLCJhY3Rpb24iOiJpbmRleCIsInBhcmFtcyI6bnVsbH0=');
    }

    public function distributorImportFromExcel() {
        return view('import.distributors');
    }

    public function postDistributorImportFromExcel(Request $request) {
        try {
            $request->validate([
              'import_file' => 'required|mimes:xlsx,xlx,xls'
            ],[
              'import_file.required' => 'File is required!'
            ]);

            Excel::import(new DistributorsImport, $request->file('import_file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                return redirect()->back()->withErrors(['msg' => $failure->errors()[0] . ' at row ' . $failure->row()]);
            }
        }
        return redirect('/');
    }

    public function outletImportFromExcel() {
        return view('import.outlets');
    }

    public function postOutletImportFromExcel(Request $request) {
        try {
          $request->validate([
            'import_file' => 'required|mimes:xlsx,xlx,xls'
          ],[
            'import_file.required' => 'File is required!'
          ]);

          Excel::import(new OutletsImport, $request->file('import_file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                return redirect()->back()->withErrors(['msg' => $failure->errors()[0] . ' at row ' . $failure->row()]);
            }
        }

       return redirect()->to('https://hessen.eyesimple.us/?kdr=eyJyb3V0ZSI6IkFwcC9QdWJsaWNQYWdlL091dGxldFJlc291cmNlIiwiYWN0aW9uIjoiaW5kZXgiLCJwYXJhbXMiOm51bGx9');
    }

    public function orderImportFromExcel() {
        return view('import.orders');
    }

    public function postOrderImportFromExcel(Request $request) {
        try {
          $request->validate([
            'import_file' => 'required|mimes:xlsx,xlx,xls'
          ],[
            'import_file.required' => 'File is required!'
          ]);

          Excel::import(new OrdersImport, $request->file('import_file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                return redirect()->back()->withErrors(['msg' => $failure->errors()[0] . ' at row ' . $failure->row()]);
            }
        }

       return redirect()->to('https://hessen.eyesimple.us/?kdr=eyJyb3V0ZSI6IkFwcC9QdWJsaWNQYWdlL09yZGVyUmVzb3VyY2UiLCJhY3Rpb24iOiJpbmRleCIsInBhcmFtcyI6bnVsbH0=');
    }
}
