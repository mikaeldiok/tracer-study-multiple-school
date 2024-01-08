@extends('tracer::frontend.layouts.app')

@section('title') {{ __("Donatur") }} @endsection

@section('content')

<div class=" container z-2">
    <div class="row">
        <div class="col mb-5">
            <button class="btn btn-sm btn-warning my-2" onclick="history.back()"><i class="fa-solid fa-arrow-left"></i> Kembali</button>
            <div class="card bg-white border-light shadow-soft no-gutters p-4">
                <div class="row">
                    <div class="col-3">
                        <img src="{{$record->photo ? asset($record->photo) : asset('img/default-avatar.jpg') }}" class="img-thumbnail img-fluid" alt="Record image">
                    </div>
                    <div class="col-9">
                        <h2 class="display-5 mt-2" style="font-size:45px"> {{$record->name}} </h2>
                        @php
                            $birthdate = Carbon\Carbon::createFromFormat('Y-m-d', $record->birth_date);
                            $age = $birthdate->diffInYears(Carbon\Carbon::now());
                        @endphp
                        <h3 class="display-6 mt-2"> {{$age}} Tahun</h3>
                        <h4 class="display-6 mt-2"> {{$record->major}} - {{$record->year_class}} </h4>
                        <table>
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold">Gender </td>
                                    <td>: {{$record->gender}}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Agama </td>
                                    <td>: {{$record->religion}}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">TB/BB </td>
                                    <td>: {{$record->height}} cm / {{$record->weight}} kg</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Keahlian </td>
                                    <td>: {{$record->skills}}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Sertifikasi </td>
                                    <td>: {{$record->certificate}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="my-3">
                            @if($record->available)
                                @if($record->checkBookedBy(auth()->user()->corporation->id ?? 0))
                                    <button class="btn btn-lg btn-danger choose-record" data-id="{{$record->id}}" id="choose-record-{{$record->id}}">BATAL</button>
                                @else
                                    <button class="btn btn-lg btn-success choose-record" data-id="{{$record->id}}" id="choose-record-{{$record->id}}">PILIH</button>
                                @endif
                            @else
                                <div class="btn-lg btn-secondary-o disabled" id="">Currently Not Available</div>
                                @if($record->checkBookedBy(auth()->user()->corporation->id ?? 0))
                                    <button class="btn btn-lg btn-danger choose-record with-warning" data-id="{{$record->id}}" id="choose-record-{{$record->id}}">BATAL</button>
                                @else
                                    <!--  -->
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')

@include("tracer::frontend.records.dynamic-scripts")

@endpush