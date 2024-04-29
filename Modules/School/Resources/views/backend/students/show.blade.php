@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ $module_title }} @stop

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item type="active" icon='{{ $module_icon }}'>{{ $module_title }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <h3>Data Alumni</h3>
        <table class="table">
            <tbody>
                <td>ID Data</td>
                    <th id="student_id">: {{ $student->id }}</th>
                </tr>
                <tr>
                    <td>Nama</td>
                    <th id="name">: {{ $student->name }}</th>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <th id="student_id">: {{ $student->addrress }}</th>
                </tr>
            </tbody>
        </table>
        <h3>Riwayat Sekolah di YPW</h3>
        <table class="table table-sm">
            <tbody>
                <?php
                    $units = $student->getUnits();
                    $year_gradates = $student->getYearGraduates();
                ?>
                <tr>
                    <th class="col-sm-2">Unit Sekolah</th>
                    <th class="col-sm-10">Tahun Lulus</th>
                </tr>
                @for($i = 0; $i<count($units);$i++)
                    <tr>
                        <td class="col-sm-2">{{config('unit-code')[$units[$i]]}}</td>
                        <td class="col-sm-10">{{$year_gradates[$i]}}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <br>
        <div class="row">
            <div class="col-8">
                <h3 class="card-title mb-0">
                    Riwayat Alumni
                </h3>
            </div>
            <!--/.col-->
            <div class="col-4">
                <div class="float-right">
                    @can('add_'.$module_name_records)
                        <x-buttons.create route='{{ route("backend.$module_name_records.createSrRecords",$student) }}' title="{{__('Create')}} {{ ucwords(Str::singular($module_name_records)) }}">Create</x-buttons.create>
                    @endcan
                </div>
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        {{ $dataTable->table() }}
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="card-footer">
        <div class="row">
            <div class="col">
                <small class="float-right text-muted">
                    Updated: {{$student->updated_at->diffForHumans()}},
                    Created at: {{$student->created_at->isoFormat('LLLL')}}
                </small>
            </div>
        </div>
    </div>
</div>
@stop

@push ('after-styles')
<!-- DataTables School and Extensions -->
<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">

@endpush

@push ('after-scripts')
<!-- DataTables School and Extensions -->
{!! $dataTable->scripts()  !!}
@endpush

