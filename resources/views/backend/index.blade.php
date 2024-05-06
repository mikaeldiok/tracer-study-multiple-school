@extends('backend.layouts.app')

@section('title') @lang("Dashboard") @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs/>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-8">
                <h4 class="card-title mb-0">@lang("Welcome to", ['name'=>config('app.name')])</h4>
                <div class="small text-muted">{{ date_today() }}</div>
            </div>
        </div>
        <hr>

        <!-- Bootstrap Tabs -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="alumni-tab" data-toggle="tab" href="#alumni" role="tab" aria-controls="alumni" aria-selected="true">List Alumni</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="statistics-tab" data-toggle="tab" href="#statistics" role="tab" aria-controls="statistics" aria-selected="false">Sebaran Studi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="reports-tab" data-toggle="tab" href="#reports" role="tab" aria-controls="reports" aria-selected="false">Rekap Gaji</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- Alumni List Tab -->
            <div class="tab-pane fade show active" id="alumni" role="tabpanel" aria-labelledby="alumni-tab">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tahun Lulus</th>
                                <th class="text-warning">KB/TK</th>
                                <th class="text-success">SD</th>
                                <th class="text-danger">SMP</th>
                                <th class="text-primary">SMA</th>
                                <th style="color: #6f42c1;">SMK</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alumniArray as $year => $alumni)
                                <tr style="font-size: 18px;">
                                    <td><a href="{{route("backend.students.index-detail",["year_graduate"=>$year])}}">{{$year}}</a></td>
                                    <td><a href="{{route("backend.students.index-detail",["year_graduate"=>$year,"unit_origin"=>1])}}">{{$alumni["KB/TK"] > 0 ? $alumni["KB/TK"] : "-" }}</a></td>
                                    <td><a href="{{route("backend.students.index-detail",["year_graduate"=>$year,"unit_origin"=>2])}}">{{$alumni["SD"] > 0 ? $alumni["SD"] : "-" }}</a></td>
                                    <td><a href="{{route("backend.students.index-detail",["year_graduate"=>$year,"unit_origin"=>3])}}">{{$alumni["SMP"] > 0 ? $alumni["SMP"] : "-" }}</a></td>
                                    <td><a href="{{route("backend.students.index-detail",["year_graduate"=>$year,"unit_origin"=>4])}}">{{$alumni["SMA"] > 0 ? $alumni["SMA"] : "-" }}</a></td>
                                    <td><a href="{{route("backend.students.index-detail",["year_graduate"=>$year,"unit_origin"=>5])}}">{{$alumni["SMK"] > 0 ? $alumni["SMK"] : "-" }}</a></td>
                                    <td>{{$alumni["total"]}}</td>
                                </tr>
                             @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PTN PTS Tab -->
            <div class="tab-pane fade" id="statistics" role="tabpanel" aria-labelledby="statistics-tab">
                <!-- <div class="row my-2">
                    <div class="col-sm-4">
                        <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                            <div class="card-body">
                                <h2 class="card-text">{{$alumni_count_ptn}}</h2>
                                <h5 class="card-title">Jumlah Alumni di PTN</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                            <div class="card-body">
                                <h2 class="card-text">{{$alumni_count_pts}}</h2>
                                <h5 class="card-title">Jumlah Alumni di PTS</h5>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div id="campusStatusChart" class="m-3 p-2" style="width: 800px;height:600px;"></div>

            </div>

            <!-- Rekap Gaji Tab -->
            <div class="tab-pane fade" id="reports" role="tabpanel" aria-labelledby="reports-tab">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Golongan</th>
                                <th>Rentang Gaji</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($incomeDistribution as $item)
                                <tr style="font-size: 18px;">
                                    <td>{{$item["name"]}}</td>
                                    <td>{{$item["tier"]}}</td>
                                    <td>{{$item["value"]}}</td>
                                </tr>
                             @endforeach
                        </tbody>
                    </table>
                </div>
                <div id="rekapGajiChart" style="width: 800px;height:600px;"></div>
            </div>
        </div>

    </div>
</div>
@endsection

@push ('after-scripts')
    <script type="text/javascript">
      // Initialize the echarts instance based on the prepared dom
      var campusStatusChart = echarts.init(document.getElementById('campusStatusChart'));

      // Specify the configuration items and data for the chart
      var optionCampusStatusChart = {
        title: {
            text: 'Sebaran Perguruan Tinggi Alumni',
            left: 'center'
        },
        tooltip: {
            trigger: 'item'
        },
        legend: {
            orient: 'vertical',
            left: 'left'
        },
        series: [
            {
            name: 'Sebaran',
            type: 'pie',
            radius: '50%',
            data: [
                { value: "{{$alumni_count_ptn}}", name: 'PTN' },
                { value: "{{$alumni_count_pts}}", name: 'PTS' },
            ],
            emphasis: {
                itemStyle: {
                shadowBlur: 10,
                shadowOffsetX: 0,
                shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            },
            label: {
                normal: {
                    show: true,
                    position: 'inside',
                    formatter: '{b}: {d}%'
                }
            },
            color: ['#007bff', '#28a745'] // Blue for PTN, Green for PTS
            }
        ]
        };

      // Display the chart using the configuration items and data just specified.
      campusStatusChart.setOption(optionCampusStatusChart);

      var rekapGajiChart = echarts.init(document.getElementById('rekapGajiChart'));
      var optionRekapGajiChart = {
        title: {
            text: 'Rentang Gaji Alumni',
            left: 'center'
        },
        tooltip: {
            trigger: 'item'
        },
        legend: {
            orient: 'vertical',
            left: 'left'
        },
        series: [
            {
            name: 'Sebaran',
            type: 'pie',
            radius: '50%',
            data: @json($incomeDistribution),
            emphasis: {
                itemStyle: {
                shadowBlur: 10,
                shadowOffsetX: 0,
                shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            },
            label: {
                normal: {
                    show: true,
                    position: 'outside',
                    formatter: '{b}: {d} %'
                }
            },
            }
        ]
        };
      rekapGajiChart.setOption(optionRekapGajiChart);

    </script>
@endpush
