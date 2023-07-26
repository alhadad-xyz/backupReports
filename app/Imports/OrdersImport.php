<?php

namespace App\Imports;

use App\Models\Distributor;
use App\Models\Outlet;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class OrdersImport implements ToCollection, WithGroupedHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $distributor = Distributor::where('distributor_email', 'like', $row['email'])->first();
            $outlet = Outlet::where('outlet_email', 'like', $row['email'])->first();
            $customer = Customer::where('customer_email', 'like', $row['email'])->first();
            if (isset($distributor) || isset($outlet) || isset($customer)) {
                if(isset($row['tanggal_invoice'])) {
                  $UNIX_DATE = ($row['tanggal_invoice'] - 25569) * 86400;
                  $row['tanggal_invoice'] = gmdate("Y-m-d", $UNIX_DATE);
                } else {
                  $row['tanggal_invoice'] = date("Y-m-d");
                }

                // Generate INV Number
                $prefix = "NJ-";
                $yearMonth = date("Ym");
                $lastInvoiceNumber = Transaction::where('invoice_no', 'like', '%' . $yearMonth . '%')->count();
                $paddedInvoiceNumber = str_pad($lastInvoiceNumber, 4, "0", STR_PAD_LEFT);
                $invoiceNumber = $prefix . $yearMonth . '-' . $paddedInvoiceNumber;
                $nextInvoiceNumber = $lastInvoiceNumber + 1;

                $transaction = Transaction::updateOrCreate([
                    'invoice_date' => $row['tanggal_invoice'],
                    'invoice_no' => $row['nomor_invoice'] ?? $invoiceNumber,
                ],[
                    'distributor_id' => isset($distributor->distributor_id) ? $distributor->distributor_id : null,
                    'outlet_id' => isset($outlet->outlet_id) ? $outlet->outlet_id : null,
                    'customer_id' => isset($customer->customer_id) ? $customer->customer_id : null,
                    'discount' => $row['discount'] ?? 0,
                    'dpp' => $row['dpp'] ?? 0,
                    'ppn' => $row['ppn'] ?? 0,
                    'grand_total' => $row['grand_total'] ?? 0,
                    'sale_return' => $row['sale_return'] ?? 0,
                    'due_payment' => $row['due_payment'] ?? 0,
                ]);

                $productNew = Product::updateOrCreate([
                    'sku' => $row['sku'],
                ],[
                    'productName' => $row['nama_produk'] ?? $productNew->productName ?? '-',
                    'category' => $row['kategori'] ?? $productNew->category ?? '-',
                    'unit' => $row['satuan'] ?? $productNew->unit ?? '-',
                    'price' => $row['harga_satuan'] ?? $productNew->price ?? 0,
                ]);

                TransactionDetail::updateOrCreate([
                    'transaction_id' => $transaction->id,
                    'product_id' => $productNew->id
                ],[
                    'qty' => $row['qty'] ?? 1,
                    'price' => $row['harga_satuan'] ?? $productNew->price,
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'email' => ['required'],
            'sku' => ['required'],
        ];
    }
}
