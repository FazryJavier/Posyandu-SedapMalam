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
        <label for="name" class="form-label">Nama Anak</label>
        <select name="name" class="custom-select rounded-0" id="formGroupExampleInput" required>
            <option value="">Pilih Nama Anak</option>
            @foreach ($children as $child)
                <option value="{{ $child->nik }}" data-weight="{{ $child->weight }}"
                    data-height="{{ $child->height }}">
                    {{ $child->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="weight" class="form-label">Berat Badan</label>
        <input type="text" name="weight" class="form-control" id="weight" required>
    </div>

    <div class="mb-3">
        <label for="height" class="form-label">Tinggi Badan</label>
        <input type="text" name="height" class="form-control" id="height" required>
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

                $('#weight').val(selectedOption.data('weight'));
                $('#height').val(selectedOption.data('height'));
            });

            $('#calculateBtn').click(function() {
                var weightValue = parseFloat($('#weight').val()) || 0;
                var heightValue = parseFloat($('#height').val()) || 0;
                var result = weightValue + heightValue;

                $('#result').val(result);
            });
        });
    </script>
@endsection
