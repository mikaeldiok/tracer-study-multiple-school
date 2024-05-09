<div class="card frontend-student shadow student-card position-relative p-3">
  <div class="row">
    <div class="col-12 text-center">
        <img class="card-img-top p-1" src="{{$student->photo ? asset($student->photo) : asset('img/default-avatar.jpg') }}" alt="Image placeholder" style="max-height:190px;min-height:190px;object-fit: contain;">
        <h4 class="card-title pt-3" style="font-size: 16px">{{\Illuminate\Support\Str::limit($student->name, 30, $end = '...')}} </h4>
        <small>{{config('unit-code')[$unit_origin_request]}}</small>
        <p>{{$year_graduate_request}}</p>
    </div>
  </div>

</div>
