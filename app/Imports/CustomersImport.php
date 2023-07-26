<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Outlet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomersImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            if(isset($row['email'])) {
                $outlet = Outlet::where('outlet_email', 'like', trim($row['outlet']))->first();
                
                $customer = Customer::firstOrCreate([
                    'customer_email' => $row['email'],
                ],[
                    'outlet_id' => isset($outlet->outlet_id) ? $outlet->outlet_id : null,
                    'customer_name' => $row['nama'],
                    'customer_country' => $row['negara'],
                    'customer_city' => $row['kota'],
                    'customer_address' => $row['alamat'],
                    'customer_contact_no' => strval($row['nomor_telp']),
                    'customer_taxable_company' => $row['nama_pengusaha_kena_pajak'],
                    'customer_npwp_address' => $row['alamat_npwp'],
                    'customer_npwp_no' => strval($row['nomor_npwp']),
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'email' => ['required'],
            'nama' => ['required']
        ];
    }
}
