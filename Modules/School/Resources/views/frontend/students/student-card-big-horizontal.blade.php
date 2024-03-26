<div class="card frontend-student shadow student-card position-relative p-3">
  <div class="row">
    <div class="col-12">
        <h4 class="card-title pt-3" style="font-size: 22px">{{\Illuminate\Support\Str::limit($student->name, 17, $end = '...')}} </h4>
        <p>({{$student->age}} tahun) - {{$student->student_id}}</p>
      @include('school::frontend.students.student-card-detail')
    </div>
  </div>

</div>
