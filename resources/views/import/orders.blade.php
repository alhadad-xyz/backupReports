@extends('import.app')

@section('title', 'Orders')

@section('content')
    <div class="container mt-5">
        <div class="card mx-auto" style="width: 32rem;">
            <div class="card-body">
                <form action="{{route('postOrderImportFromExcel')}}" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Import Orders</label>
                        <input class="form-control" type="file" name="import_file" id="import_file">
                    </div>
                    <a href="uploads/files/import_order_excel_template.xlsx" class="btn btn-success" download>Download Template</a>
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
                          <td>Email Customer/Distributors (Required)</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td>Invoice Date</td>
                          <td>Tanggal Invoice (Required)</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td>Invoice No</td>
                          <td>Nomor Invoice (Required)</td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td>Discount</td>
                          <td>Diskon Invoice (Optional)</td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td>DPP</td>
                          <td>DPP Order(Optional)</td>
                        </tr>
                        <tr>
                          <td>6</td>
                          <td>PPN</td>
                          <td>PPN Order(Optional)</td>
                        </tr>
                        <tr>
                          <td>7</td>
                          <td>Grand Total</td>
                          <td>Grand Total Order(Optional)</td>
                        </tr>
                        <tr>
                          <td>8</td>
                          <td>Sale Return</td>
                          <td>Sale Return Order (Optional)</td>
                        </tr>
                        <tr>
                          <td>9</td>
                          <td>Due Payment</td>
                          <td>Due Payment Order (Optional)</td>
                        </tr>
                        <tr>
                          <td>10</td>
                          <td>Product Name</td>
                          <td>Nama Produk (Required)</td>
                        </tr>
                        <tr>
                          <td>11</td>
                          <td>Qty</td>
                          <td>Quantity Order (Required)</td>
                        </tr>
                        <tr>
                          <td>12</td>
                          <td>Price</td>
                          <td>Harga Order (Required)</td>
                        </tr>
                        <tr>
                          <td>13</td>
                          <td>Category</td>
                          <td>Kategori Produk (Required)</td>
                        </tr>
                        <tr>
                          <td>14</td>
                          <td>Unit</td>
                          <td>Unit Produk (Required)</td>
                        </tr>
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
@endsection
