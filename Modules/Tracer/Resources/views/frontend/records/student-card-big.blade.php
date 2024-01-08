<div class="card frontend-record shadow record-card position-relative" style="width: 200px;height: 35rem;">
  @php  
  //<div class="position-absolute mx-1" style="left:0;">
  //  <a class="btn btn-sm btn-blue hover-tool rounded-bottom" href="#"><i class="fa fa-exchange"></i></a>
  //</div>
  //<div class="position-absolute mx-1" style="right:0;">
  //  <a class="btn btn-sm btn-save hover-tool rounded-bottom" href="#"><i class="fa fa-bookmark"></i></a>
  //</div>
  @endphp
  <a href="#"><img class="card-img-top p-1" src="{{$record->photo ? asset($record->photo) : asset('img/default-avatar.jpg') }}" alt="Image placeholder" style="max-height:190px;min-height:190px;object-fit: contain;"></a>
  <div class="card-body">
    @if($record->checkBookedBy(auth()->user()->corporation->id ?? 0))
      <button class="btn btn-block btn-danger choose-record" data-id="{{$record->id}}" id="choose-record-{{$record->id}}">BATAL</button>
    @else
      <button class="btn btn-block btn-success choose-record" data-id="{{$record->id}}" id="choose-record-{{$record->id}}">PILIH</button>
    @endif
    <a href="{{route('frontend.records.show',[$record->id,$record->record_id])}}">
      <h4 class="card-title pt-3" style="font-size: 22px">{{\Illuminate\Support\Str::limit($record->name, 17, $end = '...')}}</h4>
    </a>
      <h4 class="card-title" style="font-size: 19px">{{$record->major}} - {{$record->year_class}}</h4>
    <!-- detail -->

    @include('tracer::frontend.records.record-card-detail')
    
    <!-- detail end -->
    <span class="donation-time mb-3 d-block">--</span>
    
  </div>
</div>
