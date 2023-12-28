@extends('Admin.Layouts.master')

@section('title')
    Halaman Data Orang tua
@endsection

@push('script')
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script>
        $(function() {
            $("#example1").DataTable();
        });
    </script>
@endpush

@push('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.css" />
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right mb-2">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-lg">
                    Create
                </button>
            </div>
        </div>
    </div>

    {{-- Tabel Data --}}
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="col-sm-1">No</th>
                <th class="col-sm-2">Nama Ayah</th>
                <th class="col-sm-2">Nama Ibu</th>
                <th class="col-sm-4">Alamat</th>
                <th class="col-sm-2">Nomor Telpon</th>
                <th class="col-sm-1">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dataOrangtua as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->nama_ayah }}</td>
                    <td>{{ $item->nama_ibu }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->no_telpon }}</td>
                    <td>
                        <form id="delete-form-{{ $item->id }}" action="/data-orangtua/{{ $item->id }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <a href="#" type="button" class="btn btn-warning" data-toggle="modal"
                                data-target="#modal-edit-{{ $item->id }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <button type="button" class="btn btn-danger delete-btn" data-id="{{ $item->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">Data is Empty</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tambah Data --}}
    <div class="modal fade" id="modal-lg" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Orangtua</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/data-orangtua" method="POST" enctype="multipart/form-data" id="quickForm">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="nama_ayah" class="form-label">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" id="formGroupExampleInput" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_ibu" class="form-label">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control" id="formGroupExampleInput" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Rumah</label>
                            <textarea name="alamat" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="no_telpon" class="form-label">Nomor Telpon</label>
                            <input type="text" name="no_telpon" class="form-control" id="formGroupExampleInput" required>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Ubah Data --}}
    @foreach ($dataOrangtua as $item)
        <div class="modal fade" id="modal-edit-{{ $item->id }}" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ubah Data Orangtua</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/data-orangtua/{{ $item->id }}" method="POST" enctype="multipart/form-data"
                            id="updateForm-{{ $item->id }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label for="nama_ayah" class="form-label">Nama Ayah</label>
                                <input type="text" name="nama_ayah" class="form-control"
                                    value="{{ $item->nama_ayah }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="nama_ibu" class="form-label">Nama Ibu</label>
                                <input type="text" name="nama_ibu" class="form-control"
                                    value="{{ $item->nama_ibu }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2" required>{{ $item->alamat }}</textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="no_telpon" class="form-label">Nomor Telpon</label>
                                <input type="text" name="no_telpon" class="form-control"
                                    value="{{ $item->no_telpon }}" required>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        // Hapus Data
        document.addEventListener('DOMContentLoaded', function() {
            $('.delete-btn').on('click', function() {
                var itemId = $(this).data('id');
                Swal.fire({
                    title: 'Apakah anda yakin ingin menghapus data orang tua?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#delete-form-' + itemId).submit();
                    }
                });
            });
        });
    </script>
@endsection
