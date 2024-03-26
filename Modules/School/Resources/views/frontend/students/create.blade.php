@extends('frontend.layouts.app')

@section('title') {{app_name()}} @endsection

@section('content')

<div class="block-31 " style="position: relative;">
    <div class="owl-carousel loop-block-31 ">
        <div class="bg-primary" style="height:100px"  data-stellar-background-ratio="0.5">

        </div>

        </div>
    </div>
    <div class="container">
        <div class="card m-3 shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h4 class="card-title mb-0">
                            <i class="{{ $module_icon }}"></i> Daftar
                        </h4>
                        <div class="small text-muted">
                            Isilah data dibawah ini dengan sebenar-benarnya
                        </div>
                    </div>
                </div>
                <!--/.row-->

                <hr>

                @include('flash::message')
                <div class="row mt-4">
                    <div class="col">
                        {{ html()->form('POST', route("frontend.$module_name.store"))->class('form')->attributes(['enctype'=>"multipart/form-data"])->open() }}

                        @include ("school::frontend.$module_name.form")

                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <div class="form-group">
                                        {{ html()->button($text = "<i class='fas fa-pen'></i> " . ucfirst("Daftar") . "", $type = 'submit')->class('btn btn-success btn-lg') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{ html()->form()->close() }}

                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col">

                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!-- .site-section -->
@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')


@endpush

