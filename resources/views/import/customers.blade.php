@extends('import.app')

@section('title', 'Customers')

@section('content')
    <div class="container mt-5">
        <div class="card mx-auto" style="width: 80%;">
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form action="{{route('postCustomerImportFromExcel')}}" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Import Customers</label>
                        <input class="form-control" type="file" name="import_file" id="import_file">
                    </div>
                    <a href="uploads/files/[Template Customer] import_customer_excel_template.xlsx" class="btn btn-success" download>Download Template</a>
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
                          <td>Nama</td>
                          <td>Nama Customer</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td>Email</td>
                          <td>Email Customer (Required)</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td>Negara</td>
                          <td>Negara Customer</td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td>Kota</td>
                          <td>Kota Customer</td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td>Alamat</td>
                          <td>Alamat Customer</td>
                        </tr>
                        <tr>
                          <td>6</td>
                          <td>No Telp</td>
                          <td>No Telp Customer</td>
                        </tr>
                        <tr>
                          <td>7</td>
                          <td>Nama Pengusaha Kena Pajak</td>
                          <td>Nama Pengusaha Kena Pajak</td>
                        </tr>
                        <tr>
                          <td>8</td>
                          <td>Alamat NPWP</td>
                          <td>Alamat NPWP Customer</td>
                        </tr>
                        <tr>
                          <td>9</td>
                          <td>No NPWP</td>
                          <td>No NPWP Customer</td>
                        </tr>
                        <tr>
                          <td>10</td>
                          <td>Outlet</td>
                          <td>Outlet Email</td>
                        </tr>
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
@endsection
