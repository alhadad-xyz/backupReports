@extends('import.app')

@section('title', 'Orders')

@section('content')
    <div class="container mt-5">
        <div class="card mx-auto" style="width: 80%;">
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form action="{{route('postOrderImportFromExcel')}}" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Import Orders</label>
                        <input class="form-control" type="file" name="import_file" id="import_file">
                    </div>
                    <a href="uploads/files/[Template Order] import_order_excel_template.xlsx" class="btn btn-success" download>Download Template</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="card-footer">
                <label for="formFile" class="form-label">Instructions</label>
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Field</th>
                        <th scope="col">Deskripsi</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                          <td>1</td>
                          <td>Email</td>
                          <td>Email Distributor/Outlet/Customer (Required)</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td>Tanggal Invoice</td>
                          <td>Tanggal Invoice</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td>Nomor Invoice</td>
                          <td>Nomor Invoice</td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td>Discount</td>
                          <td>Diskon Invoice</td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td>DPP</td>
                          <td>DPP Order</td>
                        </tr>
                        <tr>
                          <td>6</td>
                          <td>PPN</td>
                          <td>PPN Order</td>
                        </tr>
                        <tr>
                          <td>7</td>
                          <td>Grand Total</td>
                          <td>Grand Total Order</td>
                        </tr>
                        <tr>
                          <td>8</td>
                          <td>Sale Return</td>
                          <td>Sale Return Order</td>
                        </tr>
                        <tr>
                          <td>9</td>
                          <td>Due Payment</td>
                          <td>Due Payment Order</td>
                        </tr>
                        <tr>
                          <td>10</td>
                          <td>SKU</td>
                          <td>SKU Produk (Required)</td>
                        </tr>
                        <tr>
                          <td>11</td>
                          <td>Nama Produk</td>
                          <td>Nama Produk</td>
                        </tr>
                        <tr>
                          <td>12</td>
                          <td>Qty</td>
                          <td>Quantity Order</td>
                        </tr>
                        <tr>
                          <td>13</td>
                          <td>Harga Satuan</td>
                          <td>Harga Satuan Order</td>
                        </tr>
                        <tr>
                          <td>14</td>
                          <td>Kategori</td>
                          <td>Kategori Produk</td>
                        </tr>
                        <tr>
                          <td>15</td>
                          <td>Satuan</td>
                          <td>Satuan Produk</td>
                        </tr>
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
@endsection
