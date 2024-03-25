<?php
    $corporationID = auth()->user()->corporation->id ?? 0;
?>
<div class="d-flex justify-content-between mb-1">
    <div id="students-count">
        Menampilkan {{$students->count()}} dari {{ $students->total() > 100 ? "100+" : $students->total()}} Siswa
    </div>
    <div id="students-loader">
        {{$students->links()}}
    </div>
</div>
<div class="row">
@foreach($students as $student)
    <div class="col-6 col-sm-6 pb-3 card-padding d-none d-sm-none d-md-block" style="margin-right: 0px;">
        @include('school::frontend.students.student-card-big-horizontal')
    </div>

    <div class="col-12 pb-2 card-padding d-sm-block d-md-none" style="margin-right: 0px;">
        @include('school::frontend.students.student-card-big-horizontal')
    </div>
@endforeach
</div>
<div class="d-flex justify-content-end">
    {{$students->links()}}
</div>

@push('after-scripts')
    @include("school::frontend.students.dynamic-scripts")
@endpush
