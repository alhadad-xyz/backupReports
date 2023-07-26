<?php

namespace App\Imports;

use App\Models\Distributor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class DistributorsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            if(isset($row['email'])) {
                $distributor = Distributor::firstOrCreate([
                    'distributor_email' => $row['email'],
                ],[
                    'distributor_name' => $row['nama'],
                    'distributor_country' => $row['negara'],
                    'distributor_city' => $row['kota'],
                    'distributor_address' => $row['alamat'],
                    'distributor_contact_no' => strval($row['nomor_telp']),
                    'distributor_taxable_company' => $row['nama_pengusaha_kena_pajak'],
                    'distributor_npwp_address' => $row['alamat_npwp'],
                    'distributor_npwp_no' => strval($row['nomor_npwp']),
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
