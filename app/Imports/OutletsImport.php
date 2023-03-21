<?php

namespace App\Imports;

use App\Models\DistributorHasOutlet;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class OutletsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            if(isset($row['email'])) {
                $outlet = User::firstOrCreate([
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
                    'type' => 'outlet',
                    'password' => Hash::make('12341234'),
                ]);

                $distributor = User::where('email', $row['distributor'])->orWhere('contact_no', $row['distributor'])->first();
                if($distributor) {
                    DistributorHasOutlet::create(['distributor_id' => $distributor->id, 'outlet_id' => $outlet->id]);
                }
            }
        }
    }
}
