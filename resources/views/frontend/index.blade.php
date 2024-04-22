@extends('frontend.layouts.app')

@section('title') {{app_name()}} @endsection

@section('content')

<div class="block-31" style="position: relative;">
    <div class="owl-carousel loop-block-31 ">
      <div class="block-30 block-30-sm item" style="background-image: url('images/bg_ypw.webp');" data-stellar-background-ratio="0.5">
        <div class="container">
          <div class="row align-items-center justify-content-center text-center">
            <div class="col-md-7">
              <h2 class="heading mb-5">Tracer Study<br>Warga School</h2>
              <!-- <p class="mb-0"><a href="#" class="btn btn-primary px-3 py-2">Learn More</a></p> -->
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="site-section section-counter">
    <div class="container">
      <div class="row">
        <div class="col-md-6 pr-5">
          <div class="block-48">
              <span class="block-48-text-1">Lebih dari</span>
              <div class="block-48-counter ftco-number" data-number="{{$student_count}}">0</div>
              <span class="block-48-text-1 mb-4 d-block">Alumni Warga School</span>
              <p class="mb-0"><a href="{{route('frontend.students.index')}}" class="btn btn-white px-3 py-2">Cek data alumni</a></p>
            </div>
        </div>
        <div class="col-md-6 welcome-text">
          <h2 class="display-4 mb-3">Halo Alumni Warga!</h2>
          <p class="lead"></p>
          <p class="mb-4">Terima kasih telah bersedia berpartisipasi untuk membantu kami dalam mengembangkan mutu pendidikan dengan berbagi informasi mengenai diri anda!</p>
          <p class="mb-0"><a href="{{route('frontend.students.create')}}" class="btn btn-primary px-3 py-2">Daftar Disini</a></p>
        </div>
      </div>
    </div>
  </div>


@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')


@endpush
