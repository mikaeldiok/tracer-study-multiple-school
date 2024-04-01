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
        <h3>Data Siswa</h3>
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
                    <td>Student ID</td>
                    <th id="student_id">: {{ $student->student_id }}</th>
                </tr>
            </tbody>
        </table>
        <hr>
        <div class="row">
            <div class="col-8">
                <h4 class="card-title mb-0">
                    History Siswa
                </h4>
            </div>
            <!--/.col-->
            <div class="col-4">
                <div class="float-right">
                    @can('student_area')
                        <x-buttons.create route='{{ route("frontend.$module_name_records.createSrRecords",$student) }}' title="{{__('Tambah Riwayat')}} {{ ucwords(Str::singular($module_name_records)) }}">Tambah Riwayat</x-buttons.create>
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

