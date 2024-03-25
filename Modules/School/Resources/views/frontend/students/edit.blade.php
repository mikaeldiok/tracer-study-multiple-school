@extends('school::frontend.layouts.app')

@section('title')
 {{ __("Company List") }}
@endsection

@section('content')

<section class="card-body">
    <div class="row mt-4">
        <div class="col">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{ html()->modelForm($$module_name_singular, 'PATCH', route("frontend.$module_name.update", $$module_name_singular))->class('form')->attributes(['enctype'=>"multipart/form-data"])->open() }}

            @include ("school::frontend.$module_name.form")
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        {{ html()->submit($text = icon('fas fa-save')." Save")->class('btn btn-success') }}
                    </div>
                </div>

                <div class="col-8">
                    <div class="float-right">
                        @can('delete_'.$module_name)
                        <button type="button" class="btn btn-danger delete-confirm"><i class="fas fa-trash-alt"></i></button>
                        @endcan
                        <a href="{{ route("frontend.$module_name.home") }}" class="btn btn-warning" data-toggle="tooltip" title="{{__('labels.backend.cancel')}}"><i class="fas fa-reply"></i> Cancel</a>
                    </div>
                </div>
            </div>

            {{ html()->form()->close() }}

        </div>
    </div>
</section>

@endsection

