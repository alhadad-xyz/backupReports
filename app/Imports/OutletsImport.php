<?php

namespace App\Imports;

use App\Models\Distributor;
use App\Models\Outlet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class OutletsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            if(isset($row['email'])) {
                $distributor = Distributor::where('distributor_email', 'like', trim($row['distributor']))->first();
                
                $outlet = Outlet::firstOrCreate([
                    'outlet_email' => $row['email'],
                ],[
                    'distributor_id' => isset($distributor->distributor_id) ? $distributor->distributor_id : null,
                    'outlet_name' => $row['nama'],
                    'outlet_country' => $row['negara'],
                    'outlet_city' => $row['kota'],
                    'outlet_address' => $row['alamat'],
                    'outlet_contact_no' => strval($row['nomor_telp']),
                    'outlet_taxable_company' => $row['nama_pengusaha_kena_pajak'],
                    'outlet_npwp_address' => $row['alamat_npwp'],
                    'outlet_npwp_no' => strval($row['nomor_npwp']),
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
