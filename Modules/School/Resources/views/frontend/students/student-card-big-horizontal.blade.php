<div class="card frontend-student shadow student-card position-relative p-3">
  @php
  //<div class="position-absolute mx-1" style="left:0;">
  //  <a class="btn btn-sm btn-blue hover-tool rounded-bottom" href="#"><i class="fa fa-exchange"></i></a>
  //</div>
  //<div class="position-absolute mx-1" style="right:0;">
  //  <a class="btn btn-sm btn-save hover-tool rounded-bottom" href="#"><i class="fa fa-bookmark"></i></a>
  //</div>
  @endphp
  <div class="row">
    <div class="col-4">
      <a href="{{route('frontend.students.show',[$student->id,$student->student_id])}}"><img class="card-img-top p-1" src="{{$student->photo ? asset($student->photo) : asset('img/default-avatar.jpg') }}" alt="Image placeholder" style="max-height:190px;min-height:190px;object-fit: contain;"></a>

    @if($student->isDiterima())
        @if($student->checkBookedBy($corporationID ?? 0))
            <button class="btn btn-sm btn-block btn-success disabled">Diterima</button>
        @else
            <button class="btn btn-sm btn-block btn-danger disabled">Not Available</button>
        @endif
    @else
        @if($student->checkBookedBy($corporationID ?? 0))
            <button class="btn btn-block btn-danger choose-student" data-id="{{$student->id}}" id="choose-student-{{$student->id}}">BATAL</button>
        @else
            <button class="btn btn-block btn-success choose-student" data-id="{{$student->id}}" id="choose-student-{{$student->id}}">PILIH</button>
        @endif
    @endif
      @if(!$student->available)
            @if($student->checkBookedBy($corporationID ?? 0))
                <div class="card border-success shadow mt-2">
                    <div class="card-body text-success text-center">
                        Siswa Sudah Diterima
                    </div>
                </div>
            @else
                <div class="card border-danger shadow mt-2">
                    <div class="card-body text-danger text-center">
                        <small>Siswa Diterima di Perusahaan Lain</small>
                    </div>
                </div>
            @endif
        @endif
    </div>
    <div class="col-8">
      <a href="{{route('frontend.students.show',[$student->id,$student->student_id])}}">
        <h4 class="card-title pt-3" style="font-size: 22px">{{\Illuminate\Support\Str::limit($student->name, 17, $end = '...')}} </h4>
        <p>({{$student->age}} tahun) - {{$student->student_id}}</p>
      </a>
      <h4 class="card-title" style="font-size: 19px">{{map_major($student->major)}} - {{$student->year_class}}</h4>
      @include('school::frontend.students.student-card-detail')

      <span class="font-weight-bold">Kompetensi</span>
      <small>
        <div class="row border m-2 p-1">
          <div class="col-12">
            <table>
              <tbody>
                  <tr>
                      <td class="font-weight-bold">{{setting('k1-title-'.$student->major)}}</td>
                      <td>: {{$student->k1}}</td>
                  </tr>
                  <tr>
                      <td class="font-weight-bold">{{setting('k2-title-'.$student->major)}}</td>
                      <td>: {{$student->k2}}</td>
                  </tr>
                  <tr>
                      <td class="font-weight-bold">{{setting('k3-title-'.$student->major)}}</td>
                      <td>: {{$student->k3}}</td>
                  </tr>
                  <tr>
                      <td class="font-weight-bold">{{setting('k4-title-'.$student->major)}}</td>
                      <td>: {{$student->k4}}</td>
                  </tr>
                  <tr>
                      <td class="font-weight-bold">{{setting('k5-title-'.$student->major)}}</td>
                      <td>: {{$student->k5}}</td>
                  </tr>
              </tbody>
            </table>
          </div>
        </div>
      </small>
    </div>
  </div>

    <!-- detail -->


    <!-- detail end -->
    <!-- <span class="donation-time mb-3 d-block">--</span> -->

</div>
