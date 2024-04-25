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

        <!-- Dashboard Content Area -->

        <!-- <div class="row my-2">
            <div class="col-sm-4">
                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-body">
                        <h2 class="card-text">{{$alumni_count}}</h2>
                        <h5 class="card-title">Total Alumni</h5>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-body">
                        <h2 class="card-text">{{$alumni_count_work}}</h2>
                        <h5 class="card-title">Jumlah Siswa Bekerja</h5>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="row mx-1 my-2">
            <div class="col-sm-12">
                <h4 class="card-title my-1 text-center">List Alumni</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tahun</th>
                                <th class="text-warning">KB/TK</th>
                                <th class="text-success">SD</th>
                                <th class="text-danger">SMP</th>
                                <th class="text-primary">SMA</th>
                                <th style="color: #6f42c1;">SMK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alumniArray as $year => $alumni)
                                <tr>
                                    <td><a href="{{route("backend.students.index-detail",["year_graduate"=>$year])}}">{{$year}}</a></td>
                                    <td><a href="{{route("backend.students.index-detail",["year_graduate"=>$year,"unit_origin"=>1])}}">{{$alumni["KB/TK"] > 0 ? $alumni["KB/TK"] : "-" }}</a></td>
                                    <td><a href="{{route("backend.students.index-detail",["year_graduate"=>$year,"unit_origin"=>2])}}">{{$alumni["SD"] > 0 ? $alumni["SD"] : "-" }}</a></td>
                                    <td><a href="{{route("backend.students.index-detail",["year_graduate"=>$year,"unit_origin"=>3])}}">{{$alumni["SMP"] > 0 ? $alumni["SMP"] : "-" }}</a></td>
                                    <td><a href="{{route("backend.students.index-detail",["year_graduate"=>$year,"unit_origin"=>4])}}">{{$alumni["SMA"] > 0 ? $alumni["SMA"] : "-" }}</a></td>
                                    <td><a href="{{route("backend.students.index-detail",["year_graduate"=>$year,"unit_origin"=>5])}}">{{$alumni["SMK"] > 0 ? $alumni["SMK"] : "-" }}</a></td>
                                </tr>
                             @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right">
                                    <!-- <a href="#" class="btn btn-link">Selengkapnya..</a> -->
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- / Dashboard Content Area -->

    </div>
</div>
@endsection
