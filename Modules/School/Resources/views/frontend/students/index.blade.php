@extends('frontend.layouts.app')

@section('title')
{{ __("Student") }}
@endsection

@section('content')

<div class="owl-carousel loop-block-31 mb-3">
    <div class="bg-primary" style="height:100px" data-stellar-background-ratio="0.5"></div>
</div>
<div class="px-4 z-2">
    <div class="row">
        <div class="col-lg-3 mb-5">
            <div class="card bg-white border-light shadow flex-md-row no-gutters p-4">
                <div class="card-body d-flex flex-column justify-content-between col-auto py-3">
                    <form name="filterForm" id="filterForm" method="get" action="{{ route('frontend.students.filterStudents') }}">
                        @csrf
                        @method('POST')
                        @include('school::frontend.students.filter-form')
                        <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                        <button class="btn btn-outline-danger" id="clearFilter"><i class="fa fa-times"></i>Clear Filter</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-9 mb-5">
            <div class="card bg-white border-light shadow flex-md-row no-gutters p-4">
                <div class="card-body d-flex flex-column justify-content-between col-auto py-4 px-2">
                    <section id="students">
                        @include('school::frontend.students.students-card-loader')
                    </section>
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

            $('#students a').css('color', '#dfecf6');
            $('#students').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

            var url = $(this).attr('href');
            getArticles(url);
            window.history.pushState("", "", url);
        });

        function getArticles(url) {
            $.ajax({
                url : url
            }).done(function (data) {
                $('#students').html(data);
            }).fail(function () {
                alert('Students could not be loaded.');
            });
        }

        $('#filterForm').submit(function(e) {
            e.preventDefault();

            let unitOrigin = $('#unit_origin').val();
            let yearGraduate = $('#year_graduate').val();
            if (!unitOrigin || !yearGraduate) {
                alert('Unit dan Tahun Lulus harus diisi');
                return; // Prevent form submission
            }
            $('#submit').html('Please Wait...');
            $("#submit").attr("disabled", true);

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response) {
                    $('#submit').html('Submit');
                    $("#submit").attr("disabled", false);
                    $('#students').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while processing your request.');
                }
            });
        });

        $('body').on('click', '#clearFilter', function(e) {
            e.preventDefault();
            $("#filterForm").trigger("reset");

            $('#name').val('');
            $('#unit_origin').val('');
            $('#year_graduate').val('');

        });
    });
</script>

@endpush
