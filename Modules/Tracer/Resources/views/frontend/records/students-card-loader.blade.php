
<div class="d-flex justify-content-between mb-1">
    <div id="records-count">
        Menampilkan {{$records->count()}} dari {{ $records->total() > 100 ? "100+" : $records->total()}} Siswa
    </div>
    <div id="records-loader">
        {{$records->links()}}
    </div>
</div>
<div class="row">
@foreach($records as $record)
    <div class="col-3 pb-3 card-padding" style="margin-right: 0px;">
        @include('tracer::frontend.records.record-card-big')
    </div>

@endforeach
</div>
<div class="d-flex justify-content-end">
    {{$records->links()}}
</div>

@push('after-scripts')
    @include("tracer::frontend.records.dynamic-scripts")
@endpush
