@extends('Admin.Layouts.master')

@section('title')
    Halaman Timbang Anak
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
    <div class="mb-3">
        <label for="nama_anak" class="form-label">Nama Anak</label>
        <select name="nama_anak" class="custom-select rounded-0" id="formGroupExampleInput" required>
            <option value="">Pilih Nama Anak</option>
            @foreach ($dataAnak as $item)
                <option value="{{ $item->nik_anak }}" data-berat="{{ $item->berat_badan }}"
                    data-tinggi="{{ $item->tinggi_badan }}">
                    {{ $item->nama_anak }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="berat_badan" class="form-label">Berat Badan</label>
        <input type="text" name="berat_badan" class="form-control" id="berat_badan" required>
    </div>

    <div class="mb-3">
        <label for="tinggi_badan" class="form-label">Tinggi Badan</label>
        <input type="text" name="tinggi_badan" class="form-control" id="tinggi_badan" required>
    </div>

    <button type="button" class="btn btn-primary" id="calculateBtn">Calculate</button>

    <div class="mb-3">
        <label for="result" class="form-label">Hasil Perhitungan Timbang Anak</label>
        <input type="text" name="result" class="form-control" id="result" readonly>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#formGroupExampleInput').change(function() {
                var selectedOption = $(this).find(':selected');

                $('#berat_badan').val(selectedOption.data('berat'));
                $('#tinggi_badan').val(selectedOption.data('tinggi'));
            });

            $('#calculateBtn').click(function() {
                var beratBadan = parseFloat($('#berat_badan').val()) || 0;
                var tinggiBadan = parseFloat($('#tinggi_badan').val()) || 0;
                var result = beratBadan + tinggiBadan;

                $('#result').val(result);
            });
        });
    </script>
@endsection
