@extends('import.app')

@section('title', 'Outlets')

@section('content')
    <div class="container mt-5">
        <div class="card mx-auto" style="width: 32rem;">
            <div class="card-body">
                <form action="{{route('postOutletImportFromExcel')}}" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Import Outlets</label>
                        <input class="form-control" type="file" name="import_file" id="import_file">
                    </div>
                    <a href="uploads/files/import_outlet_excel_template.xlsx" class="btn btn-success" download>Download Template</a>
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
                          <td>Nama Outlet (Required)</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td>Email</td>
                          <td>Email Outlet (Required)</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td>Negara</td>
                          <td>Negara Outlet (Optional)</td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td>Kota</td>
                          <td>Kota Outlet (Optional)</td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td>Alamat</td>
                          <td>Alamat Outlet (Optional)</td>
                        </tr>
                        <tr>
                          <td>6</td>
                          <td>No Telp</td>
                          <td>No Telp Outlet (Optional)</td>
                        </tr>
                        <tr>
                          <td>7</td>
                          <td>Nama Pengusaha Kena Pajak</td>
                          <td>Nama Pengusaha Kena Pajak (Optional)</td>
                        </tr>
                        <tr>
                          <td>8</td>
                          <td>Alamat NPWP</td>
                          <td>Alamat NPWP Outlet (Optional)</td>
                        </tr>
                        <tr>
                          <td>9</td>
                          <td>No NPWP</td>
                          <td>No NPWP Outlet (Optional)</td>
                        </tr>
                        <tr>
                          <td>10</td>
                          <td>Distributor</td>
                          <td>Distributor Email / Contact No (Optional)</td>
                        </tr>
                    </tbody>
                  </table>
            </div>
        </div>
    </div>
@endsection
