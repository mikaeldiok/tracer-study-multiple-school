@extends('tracer::frontend.layouts.app')

@section('title') {{ __("Donatur") }} @endsection

@section('content')

<div class="px-4 z-2">
    <div class="row">

    <div class="col-lg-3 mb-5">
            <div class="card bg-white border-light shadow flex-md-row no-gutters p-4">
                <div class="card-body d-flex flex-column justify-content-between col-auto py-3">
                    <form name="filterForm" id="filterForm" method="post" action="javascript:void(0)">
                        @csrf

                        {{ method_field('POST') }}
                        @include('tracer::frontend.records.filter-form')
                        <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                        <button class="btn btn-danger-o" id="clearFilter"><i class="fa fa-times"></i>Clear Filter</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-9 mb-5">
            <div class="card bg-white border-light shadow flex-md-row no-gutters p-4">
                <div class="card-body d-flex flex-column justify-content-between col-auto py-4 px-2">
                    @if (count($records) > 0)
                        <section id="records">
                            @include('tracer::frontend.records.records-card-loader')
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')

<script type="text/javascript">
    $(function() {
        $('body').on('click', '.pagination a', function(e) {
            e.preventDefault();

            $('#records a').css('color', '#dfecf6');
            $('#records').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

            var url = $(this).attr('href');  
            getArticles(url);
            window.history.pushState("", "", url);
        });

        function getArticles(url) {
            $.ajax({
                url : url  
            }).done(function (data) {
                $('#records').html(data);  
            }).fail(function () {
                alert('Records could not be loaded.');
            });
        }
    });

    $(function() {
        $('body').on('click', '#clearFilter', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{route('frontend.records.filterRecords')}}",
                type: "GET",
                data: null,
                success: function(response) {
                    $("#filterForm").trigger("reset");
                    
                    $('#skills').multiselect('refresh');

                    $('#certificate').multiselect('refresh');

                    $('#major').multiselect('refresh');

                    $('#year_class').multiselect('refresh');

                    $('#records').html(response);  
                }
            });
        });
    });
</script>
<script >
    if ($("#filterForm").length > 0) {
        $("#filterForm").validate({
            rules: {
                // name: {
                //     required: true,
                //     maxlength: 50
                // },
                // email: {
                //     required: true,
                //     maxlength: 50,
                //     email: true,
                // },
                // message: {
                //     required: true,
                //     maxlength: 300
                // },
            },
            messages: {
                // name: {
                //     required: "Please enter name",
                //     maxlength: "Your name maxlength should be 50 characters long."
                // },
                // email: {
                //     required: "Please enter valid email",
                //     email: "Please enter valid email",
                //     maxlength: "The email name should less than or equal to 50 characters",
                // },
                // message: {
                //     required: "Please enter message",
                //     maxlength: "Your message name maxlength should be 300 characters long."
                // },
            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // $('#submit').html('Please Wait...');
                // $("#submit").attr("disabled", true);
                $.ajax({
                    url: "{{route('frontend.records.filterRecords')}}",
                    type: "GET",
                    data: $('#filterForm').serialize(),
                    success: function(response) {
                        // $('#submit').html('Submit');
                        // $("#submit").attr("disabled", false);
                        // document.getElementById("filterForm").reset();

                        $('#records').html(response);  

                    }
                });
            }
        })
    } 
</script>
@endpush