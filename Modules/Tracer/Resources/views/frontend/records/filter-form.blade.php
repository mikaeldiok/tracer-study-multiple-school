@php
    $options = Modules\Tracer\Services\RecordService::prepareFilter();
@endphp
<div class="row">
    <div class="col-12 col-sm-4">
        <div class="form-group">
            <?php
            $field_name = 'unit_origin';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = "-- Pilih --";
            $required = "";
            $select_options = config('unit-code');
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-9">
        <div class="form-group">
            <?php
            $field_name = 'year_class';
            $field_lable = __("tracer::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "";
            $select_options = $options['year_class'];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->multiselect($field_name,$select_options)->name($field_name.'[]')->class('form-control')->attributes(["$required",'multiple' => 'multiple']) }}
        </div>
    </div>
</div>

<!-- Select2 Library -->
<x-library.select2 />
<x-library.datetime-picker />

@push('after-styles')
<!-- File Manager -->
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
@endpush

@push ('after-scripts')

<!-- Date Time Picker & Moment Js-->
<script type="text/javascript">

$(document).ready(function() {
    $('#skills').multiselect({
            enableFiltering: true,
        });

    $('#certificate').multiselect({
            enableFiltering: true,
        });

    $('#major').multiselect({
            enableFiltering: true,
        });


    $('#year_class').multiselect({
            enableFiltering: true,
        });
});

</script>

@endpush
