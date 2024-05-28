@php
    $options = Modules\School\Services\StudentService::prepareFilter();
@endphp
<div class="row">
    <div class="col">
        <h1>Filter</h1>
        <small>Masukkan kriteria yang anda inginkan dibawah ini</small>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-12 col-sm-9">
        <div class="form-group">
            <?php
            $field_name = 'name';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = "";
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-9">
        <div class="form-group">
            <?php
            $field_name = 'unit_origin';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = "-- Pilih --";
            $required = "";
            $select_options = config('unit-code');
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-9">
        <div class="form-group">
            <?php
            $field_name = 'year_graduate';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "";
            $years = range(1970, date('Y'));
            $select_options = array_combine($years, $years);
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, array_reverse($select_options,true))->class('form-control select2')->attributes(["$required"]) }}
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


    // $('#year_class').multiselect({
    //         enableFiltering: true,
    //     });
});

</script>

@endpush
