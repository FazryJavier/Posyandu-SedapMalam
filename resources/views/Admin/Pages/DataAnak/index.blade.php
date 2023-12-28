@extends('Admin.Layouts.master')

@section('title')
    Halaman Data Anak
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

    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="col-sm-1">No</th>
                <th class="col-sm-2">NIK</th>
                <th class="col-sm-2">Nama</th>
                <th class="col-sm-1">Umur</th>
                <th class="col-sm-1">Berat Badan</th>
                <th class="col-sm-1">Tinggi Badan</th>
                <th class="col-sm-1">Ling. Kepala</th>
                <th class="col-sm-1">Ling. Lengan</th>
                <th class="col-sm-1">BMI</th>
                <th class="col-sm-1">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dataAnak as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->nik_anak }}</td>
                    <td>{{ $item->nama_anak }}</td>
                    <td>{{ $item->umur }}</td>
                    <td>{{ $item->berat_badan }}</td>
                    <td>{{ $item->tinggi_badan }}</td>
                    <td>{{ $item->lingkar_kepala }}</td>
                    <td>{{ $item->lingkar_lengan }}</td>
                    <td>{{ $item->bmi }}</td>
                    <td>
                        <form id="delete-form-{{ $item->nik_anak }}" action="/data-anak/{{ $item->nik_anak }}"
                            method="POST">
                            <a href="#" type="button" class="btn btn-warning" data-toggle="modal"
                                data-target="#modal-edit-{{ $item->nik_anak }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger delete-btn" data-id="{{ $item->nik_anak }}">
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
                    <h4 class="modal-title">Tambah Data Anak</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/data-anak" method="POST" enctype="multipart/form-data" id="quickForm">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="nik_anak" class="form-label">NIK Anak</label>
                            <input type="text" name="nik_anak" class="form-control" id="formGroupExampleInput" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_anak" class="form-label">Nama Anak</label>
                            <input type="text" name="nama_anak" class="form-control" id="formGroupExampleInput" required>
                        </div>
                        <div class="mb-3">
                            <label for="umur" class="form-label">Umur</label>
                            <input type="text" name="umur" class="form-control" id="formGroupExampleInput" required>
                        </div>
                        <div class="mb-3">
                            <label for="berat_badan" class="form-label">Berat Badan</label>
                            <input type="text" name="berat_badan" class="form-control" id="formGroupExampleInput">
                        </div>
                        <div class="mb-3">
                            <label for="tinggi_badan" class="form-label">Tinggi Badan</label>
                            <input type="text" name="tinggi_badan" class="form-control" id="formGroupExampleInput">
                        </div>
                        <div class="mb-3">
                            <label for="lingkar_kepala" class="form-label">Lingkar Kepala</label>
                            <input type="text" name="lingkar_kepala" class="form-control" id="formGroupExampleInput">
                        </div>
                        <div class="mb-3">
                            <label for="lingkar_lengan" class="form-label">Lingkar Lengan</label>
                            <input type="text" name="lingkar_lengan" class="form-control" id="formGroupExampleInput">
                        </div>
                        <div class="mb-3">
                            <label for="bmi" class="form-label">BMI</label>
                            <input type="text" name="bmi" class="form-control" id="formGroupExampleInput">
                        </div>
                        <div class="mb-3">
                            <label for="IdOrangtua" class="form-label">Nama Ibu</label>
                            <select name="IdOrangtua" class="custom-select rounded-0" id="formGroupExampleInput"
                                required>
                                <option value="">Pilih Nama Orang Tua</option>
                                @foreach ($dataOrangtua as $dataIbu)
                                    <option value="{{ $dataIbu->id }}">{{ $dataIbu->nama_ibu }}</option>
                                @endforeach
                            </select>
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
    @foreach ($dataAnak as $item)
        <div class="modal fade" id="modal-edit-{{ $item->nik_anak }}" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ubah Data Anak</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/data-anak/{{ $item->nik_anak }}" method="POST" enctype="multipart/form-data"
                            id="updateForm-{{ $item->nik_anak }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label for="nik_anak" class="form-label">NIK Anak</label>
                                <input type="text" name="nik_anak" class="form-control"
                                    value="{{ $item->nik_anak }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="nama_anak" class="form-label">Nama Anak</label>
                                <input type="text" name="nama_anak" class="form-control"
                                    value="{{ $item->nama_anak }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="umur" class="form-label">Umur</label>
                                <input type="text" name="umur" class="form-control" value="{{ $item->umur }}"
                                    required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="berat_badan" class="form-label">Berat Badan</label>
                                <input type="text" name="berat_badan" class="form-control"
                                    value="{{ $item->berat_badan }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="tinggi_badan" class="form-label">Tinggi Badan</label>
                                <input type="text" name="tinggi_badan" class="form-control"
                                    value="{{ $item->tinggi_badan }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="lingkar_kepala" class="form-label">Lingkar Kepala</label>
                                <input type="text" name="lingkar_kepala" class="form-control"
                                    value="{{ $item->lingkar_kepala }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="lingkar_lengan" class="form-label">Lingkar Lengan</label>
                                <input type="text" name="lingkar_lengan" class="form-control"
                                    value="{{ $item->lingkar_lengan }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="bmi" class="form-label">BMI</label>
                                <input type="text" name="bmi" class="form-control" value="{{ $item->bmi }}">
                            </div>
                            <div class="mb-3">
                                <label for="IdOrangtua" class="form-label">Nama Ibu</label>
                                <select name="IdOrangtua" class="custom-select rounded-0" id="formGroupExampleInput"
                                    required>
                                    <option value="">Pilih Nama Orang Tua</option>
                                    @foreach ($dataOrangtua as $dataIbu)
                                        <option value="{{ $dataIbu->id }}"
                                            {{ $dataIbu->id == $item->IdOrangtua ? 'selected' : '' }}>
                                            {{ $dataIbu->nama_ibu }}
                                        </option>
                                    @endforeach
                                </select>
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
                    title: 'Apakah anda yakin ingin menghapus data anak?',
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
