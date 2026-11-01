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
            @forelse ($children as $key => $child)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $child->nik }}</td>
                    <td>{{ $child->name }}</td>
                    <td>{{ $child->age }}</td>
                    <td>{{ $child->weight }}</td>
                    <td>{{ $child->height }}</td>
                    <td>{{ $child->head_circumference }}</td>
                    <td>{{ $child->arm_circumference }}</td>
                    <td>{{ $child->bmi }}</td>
                    <td>
                        <form id="delete-form-{{ $child->nik }}" action="/data-anak/{{ $child->nik }}"
                            method="POST">
                            <a href="#" type="button" class="btn btn-warning" data-toggle="modal"
                                data-target="#modal-edit-{{ $child->nik }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger delete-btn" data-id="{{ $child->nik }}">
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
                            <label for="nik" class="form-label">NIK Anak</label>
                            <input type="text" name="nik" class="form-control" id="formGroupExampleInput" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Anak</label>
                            <input type="text" name="name" class="form-control" id="formGroupExampleInput" required>
                        </div>
                        <div class="mb-3">
                            <label for="age" class="form-label">Umur</label>
                            <input type="text" name="age" class="form-control" id="formGroupExampleInput" required>
                        </div>
                        <div class="mb-3">
                            <label for="weight" class="form-label">Berat Badan</label>
                            <input type="text" name="weight" class="form-control" id="formGroupExampleInput">
                        </div>
                        <div class="mb-3">
                            <label for="height" class="form-label">Tinggi Badan</label>
                            <input type="text" name="height" class="form-control" id="formGroupExampleInput">
                        </div>
                        <div class="mb-3">
                            <label for="head_circumference" class="form-label">Lingkar Kepala</label>
                            <input type="text" name="head_circumference" class="form-control" id="formGroupExampleInput">
                        </div>
                        <div class="mb-3">
                            <label for="arm_circumference" class="form-label">Lingkar Lengan</label>
                            <input type="text" name="arm_circumference" class="form-control" id="formGroupExampleInput">
                        </div>
                        <div class="mb-3">
                            <label for="bmi" class="form-label">BMI</label>
                            <input type="text" name="bmi" class="form-control" id="formGroupExampleInput">
                        </div>
                        <div class="mb-3">
                            <label for="guardian_id" class="form-label">Nama Ibu</label>
                            <select name="guardian_id" class="custom-select rounded-0" id="formGroupExampleInput"
                                required>
                                <option value="">Pilih Nama Orang Tua</option>
                                @foreach ($guardians as $guardian)
                                    <option value="{{ $guardian->id }}">{{ $guardian->mother_name }}</option>
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
    @foreach ($children as $child)
        <div class="modal fade" id="modal-edit-{{ $child->nik }}" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ubah Data Anak</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="/data-anak/{{ $child->nik }}" method="POST" enctype="multipart/form-data"
                            id="updateForm-{{ $child->nik }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label for="nik" class="form-label">NIK Anak</label>
                                <input type="text" name="nik" class="form-control"
                                    value="{{ $child->nik }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nama Anak</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ $child->name }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="age" class="form-label">Umur</label>
                                <input type="text" name="age" class="form-control" value="{{ $child->age }}"
                                    required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="weight" class="form-label">Berat Badan</label>
                                <input type="text" name="weight" class="form-control"
                                    value="{{ $child->weight }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="height" class="form-label">Tinggi Badan</label>
                                <input type="text" name="height" class="form-control"
                                    value="{{ $child->height }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="head_circumference" class="form-label">Lingkar Kepala</label>
                                <input type="text" name="head_circumference" class="form-control"
                                    value="{{ $child->head_circumference }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="arm_circumference" class="form-label">Lingkar Lengan</label>
                                <input type="text" name="arm_circumference" class="form-control"
                                    value="{{ $child->arm_circumference }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="bmi" class="form-label">BMI</label>
                                <input type="text" name="bmi" class="form-control" value="{{ $child->bmi }}">
                            </div>
                            <div class="mb-3">
                                <label for="guardian_id" class="form-label">Nama Ibu</label>
                                <select name="guardian_id" class="custom-select rounded-0" id="formGroupExampleInput"
                                    required>
                                    <option value="">Pilih Nama Orang Tua</option>
                                    @foreach ($guardians as $guardian)
                                        <option value="{{ $guardian->id }}"
                                            {{ $guardian->id == $child->guardian_id ? 'selected' : '' }}>
                                            {{ $guardian->mother_name }}
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
