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
@if($students->count() > 0)
    @foreach($students as $student)
        <div class="col-6 col-sm-4 pb-3 card-padding d-none d-sm-none d-md-block" style="margin-right: 0px;">
            @include('school::frontend.students.student-card-medium')
        </div>
    @endforeach
@endif
</div>
<div class="d-flex justify-content-end">
    {{$students->links()}}
</div>

