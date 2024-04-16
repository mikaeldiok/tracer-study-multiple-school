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

        <div class="row my-2">
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
        </div>


        <!-- / Dashboard Content Area -->

    </div>
</div>
@endsection
