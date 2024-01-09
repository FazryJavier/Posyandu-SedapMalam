@extends('Admin.Layouts.master')

@section('title')
    Halaman Grafik Perkembangan
@endsection

@push('script')
    {{-- <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script> --}}
    <script src="{{ asset('adminlte/plugins/uplot/uPlot.cjs.js ') }}"></script>
@endpush

@push('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.css" />
    <!-- uPlot -->
    <link rel="stylesheet" href="adminlte/plugins/uplot/uPlot.min.css">
@endpush

@section('content')
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Data Grafik </h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="chart">
                <canvas id="lineChart"
                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <script>
        $(function() {
            /* uPlot
             * -------
             * Here we will create a few charts using uPlot
             */

            function getSize(elementId) {
                return {
                    width: document.getElementById(elementId).offsetWidth,
                    height: document.getElementById(elementId).offsetHeight,
                }
            }

            let data = [
                [0, 1, 2, 3, 4, 5, 6],
                [28, 48, 40, 19, 86, 27, 90],
                [65, 59, 80, 81, 56, 55, 40]
            ];

            //--------------
            //- AREA CHART -
            //--------------
            const optsLineChart = {
                ...getSize('lineChart'),
                scales: {
                    x: {
                        time: false,
                    },
                    y: {
                        range: [0, 100],
                    },
                },
                series: [{},
                    {
                        fill: 'transparent',
                        width: 5,
                        stroke: 'rgba(60,141,188,1)',
                    },
                    {
                        stroke: '#c1c7d1',
                        width: 5,
                        fill: 'transparent',
                    },
                ],
            };

            let lineChart = new uPlot(optsLineChart, data, document.getElementById('lineChart'));

            window.addEventListener("resize", e => {
                lineChart.setSize(getSize('lineChart'));
            });
        })
    </script>
@endsection
