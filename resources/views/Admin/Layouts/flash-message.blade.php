{{-- SweetAlert2 CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/js/app.js"></script>

@if ($message = Session::get('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ $message }}',
        });
    </script>
@endif

@if ($message = Session::get('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ $message }}',
        });
    </script>
@endif

@if ($message = Session::get('warning'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Warning!',
            text: '{{ $message }}',
        });
    </script>
@endif

@if ($message = Session::get('info'))
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Info!',
            text: '{{ $message }}',
        });
    </script>
@endif

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Please check the form below for errors',
            html: '{!! implode('<br>', $errors->all()) !!}',
        });
    </script>
@endif
