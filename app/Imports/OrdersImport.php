<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;
use Illuminate\Support\Facades\Hash;

class OrdersImport implements ToCollection, WithGroupedHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $user = User::where('email', $row['email'])->first();
            if (isset($user)) {
                $UNIX_DATE = ($row['invoice_date'] - 25569) * 86400;
                $row['invoice_date'] = gmdate("Y-m-d", $UNIX_DATE);

                $products = [];
                foreach($row['product_name'] as $key => $value) {
                    $products[$key]['productName'] = strtoupper($value);
                }

                foreach($row['qty'] as $key => $value) {
                    $products[$key]['qty'] = $value;
                }

                foreach($row['price'] as $key => $value) {
                    $products[$key]['price'] = $value;
                }

                foreach($row['unit'] as $key => $value) {
                    $products[$key]['unit'] = strtoupper($value);
                }

                foreach($row['category'] as $key => $value) {
                    $products[$key]['category'] = strtoupper($value);
                }

                $transaction = Transaction::firstOrCreate([
                    'invoice_date' => $row['invoice_date'],
                    'invoice_no' => $row['invoice_no'],
                    'user_id' => $user->id,
                ],[
                    'discount' => $row['discount'],
                    'dpp' => $row['dpp'],
                    'ppn' => $row['ppn'],
                    'grand_total' => $row['grand_total'],
                    'sale_return' => $row['sale_return'],
                    'due_payment' => $row['due_payment'],
                ]);

                foreach($products as $key => $product) {
                    $productNew = Product::firstOrCreate([
                        'productName' => $product['productName'],
                    ],[
                        'category' => $product['category'],
                        'unit' => $product['unit'],
                        'price' => $product['price'],
                    ]);

                    TransactionDetail::firstOrCreate([
                        'transaction_id' => $transaction->id,
                        'product_id' => $productNew->id
                    ],[
                        'qty' => $product['qty'],
                        'price' => $product['price'],
                    ]);
                }
            }
        }
    }
}
