{{ html()->hidden("student_id")->value($student_id ?? "0") }}
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <?php
            $field_name = 'level_id';
            $field_lable = label_case("saat ini sedang?");
            $field_placeholder = "-- Pilih --";
            $required = "required";
            $select_options = $options['level'];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group campus-input">
            <?php
            $field_name = 'campus_status';
            $field_lable = label_case("Jenis Kampus");
            $field_placeholder = "-- Pilih --";
            $required = "";
            $select_options = [
                "PTN" => "PTN",
                "PTS" => "PTS"
            ];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'name';
            $field_lable = __("tracer::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required", 'aria-label'=>'Image']) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'role';
            $field_lable = __("tracer::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required", 'aria-label'=>'Image']) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'province';
            $field_lable = __("tracer::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            $select_options = config('provinces');
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control select2')->attributes(["$required"]) }}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'city';
            $field_lable = __("tracer::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            $select_options = config('regencies');
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control select2')->attributes(["$required"]) }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'enter_at';
            $field_lable = __("tracer::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->number($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'graduate_at';
            $field_lable = __("tracer::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->number($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
<div class="col-12 col-sm-6">
        <div class="form-group work-input">
            <?php
            $field_name = 'income';
            $field_lable = label_case("Pemasukan perbulan");
            $field_placeholder = "-- Pilih --";
            $required = "required";
            $select_options = [
                                '0' => "< Rp2.000.000",
                                '1' => "Rp2.000.000 - Rp6.000.000",
                                '2' => "Rp6.000.000 - Rp10.000.000",
                                '3' => "> Rp10.000.000"
                            ];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
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

<script type="text/javascript">
    $(document).ready(function() {
        function toggleWorkInput() {
            var selectElement = document.getElementById('level_id');
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var levelValue = selectedOption.innerHTML;

            console.log(levelValue);
            if (levelValue === 'Bekerja') {
                $('.work-input').show();
                $('#income').attr('required', 'required');

                $('.campus-input').hide();
                $('#campus_status').val("");
                $('#campus_status').removeAttr('required');
            }else if (levelValue === 'Kuliah') {
                $('.campus-input').show();
                $('#campus_status').attr('required', 'required');

                $('.work-input').hide();
                $('#income').val("");
                $('#income').removeAttr('required');
            } else {
                $('.work-input').hide();
                $('#income').val("");
                $('#income').removeAttr('required');

                $('.campus-input').hide();
                $('#campus_status').val("");
                $('#campus_status').removeAttr('required');
            }
        }

        // Initial call to toggleWorkInput
        toggleWorkInput();

        // Event listener for level_id change
        $('#level_id').on('change', function() {
            toggleWorkInput();
        });
    });


    $(function() {
        var date = moment("{{$$module_name_singular->enter_at ?? ''}}", 'YYYY-MM-DD').toDate();
        $('.datetime').datetimepicker({
            format: 'YYYY',
            viewMode: 'years', // Only show years
            date: date,
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar-alt',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'far fa-calendar-check',
                clear: 'far fa-trash-alt',
                close: 'fas fa-times'
            }
        });
    });

</script>
@endpush
