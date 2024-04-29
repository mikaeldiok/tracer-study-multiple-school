@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ $module_title }} @stop


@section('content')
<div class="card">
    <div class="card-body">
        <h3>Data Alumni</h3>
            <a href="{{route("frontend.students.edit",$student)}}" class="btn btn-primary my-2"><i class="fas fa-edit"></i>edit</a>
        <div class="row mb-3">
             <div class="col-12 col-sm-2">
                <img src="{{asset($student->photo)}}" class="img-thumbnail" style="max-height:200px; max-width:150px;" />
             </div>
             <div class="col-12 col-sm-9">
                <h4>Biodata</h4>
                <table class="table table-sm">
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
             </div>
        </div>
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

