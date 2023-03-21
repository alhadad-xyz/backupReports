<?php

namespace App\Imports;

use App\Models\OutletHasCustomer;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class CustomersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            if(isset($row['email'])) {
                $customer = User::firstOrCreate([
                    'email' => $row['email'],
                ],[
                    'name' => $row['name'],
                    'country' => $row['country'],
                    'city' => $row['city'],
                    'address' => $row['address'],
                    'contact_no' => $row['contact_no'],
                    'taxable_company' => $row['taxable_company'],
                    'npwp_address' => $row['npwp_address'],
                    'npwp_no' => $row['npwp_no'],
                    'type' => 'customer',
                    'password' => Hash::make('12341234'),
                ]);

                $outlet = User::where('email', $row['outlet'])->first();
                if($outlet) {
                    OutletHasCustomer::create(['outlet_id' => $outlet->id, 'customer_id' => $customer->id]);
                }
            }
        }
    }
}
