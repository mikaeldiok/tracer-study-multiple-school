@extends('school::frontend.layouts.app')

@section('title')
 {{ __("Company List") }}
@endsection

@section('content')

<section class="card-body">
    <div class="row mt-4">
        <div class="col">
            <div class="table-responsive">
                <table class="table">
                    {{ $dataTable->table() }}
                </table>
            </div>
        </div>
    </div>
</section>

@endsection

@push ('after-styles')
<!-- DataTables School and Extensions -->
<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">

@endpush

@push ('after-scripts')
<!-- DataTables School and Extensions -->
{!! $dataTable->scripts()  !!}

<script>
    $(document).ready(function(){

        // Function to handle delete button click
        function handleConfirmButton(id,div) {
            $.ajax({
                type: "POST",
                url: "{{route('frontend.students.confirmation')}}",
                data: {
                    "bookings_id" : id,
                    "_method": "POST",
                    "_token": "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (!response.error) {
                        // Refresh DataTable
                        $('#bookings-table').DataTable().ajax.reload();

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            showCloseButton: true,
                            timer: 3000,
                            timerProgressBar: true,
                        })

                        Toast.fire({
                            icon: 'success',
                            title: 'Sukses! Berhasil mengirim notifikasi ke perusahaan'
                        })

                        div.html('<div class="text-success"><i class="fas fa-check"></i> Sudah Dikonfirmasi</div>');

                    } else {

                        $('#bookings-table').DataTable().ajax.reload();
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            showCloseButton: true,
                            timer: 3000,
                            timerProgressBar: true,
                        })

                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        })
                    }
                },
                error: function (request, status, error) {

                    $('#bookings-table').DataTable().ajax.reload();
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        showCloseButton: true,
                        timer: 3000,
                        timerProgressBar: true,
                    })

                    Toast.fire({
                        icon: 'error',
                        title: 'An error occurred! Please try again later.'
                    })
                }
            });
        }

        function handleDeclineButton(id,decline_note,div) {
            $.ajax({
                type: "POST",
                url: "{{route('frontend.students.declination')}}",
                data: {
                    "bookings_id" : id,
                    "decline_note" : decline_note,
                    "_method": "POST",
                    "_token": "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (!response.error) {
                        // Refresh DataTable
                        $('#bookings-table').DataTable().ajax.reload();

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            showCloseButton: true,
                            timer: 3000,
                            timerProgressBar: true,
                        })

                        Toast.fire({
                            icon: 'success',
                            title: 'Sukses! Berhasil mengirim notifikasi ke perusahaan'
                        })

                        div.html('<div class="text-danger"><i class="fas fa-times"></i>Telah Digugurkan</div>');

                    } else {

                        $('#bookings-table').DataTable().ajax.reload();
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            showCloseButton: true,
                            timer: 3000,
                            timerProgressBar: true,
                        })

                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        })
                    }
                },
                error: function (request, status, error) {

                    $('#bookings-table').DataTable().ajax.reload();
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        showCloseButton: true,
                        timer: 3000,
                        timerProgressBar: true,
                    })

                    Toast.fire({
                        icon: 'error',
                        title: 'An error occurred! Please try again later.'
                    })
                }
            });
        }

        $(document).on('click', '.btn-confirm-bookings', function () {
            var id = $(this).attr('bookings-id');
            Swal.fire({
                title: 'Konfirmasi untuk melanjutkan?',
                text: 'Perusahaan akan diberitahu bahwa anda bersedia melanjutkan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, konfirmasi'
            }).then((result) => {
                if (result.isConfirmed) {
                    var div = $(this).closest('div');
                    div.html('please wait...');
                    handleConfirmButton(id,div);
                }
            });
        });

        $(document).on('click', '.btn-decline-bookings', function () {
            var id = $(this).attr('bookings-id');
            Swal.fire({
                title: 'Konfirmasi untuk Mengundurkan Diri?',
                text: 'Perusahaan akan diberitahu bahwa anda tidak bersedia melanjutkan',
                input: "textarea",
                inputLabel: "Masukkan alasan utama anda",
                inputAttributes: {
                    "aria-label": "Type your reason here"
                },
                showCancelButton: true,
                confirmButtonColor: '#DC3545',
                cancelButtonColor: '#28a745',
                confirmButtonText: 'Ya, konfirmasi untuk Mundur',
                inputValidator: (value) => {
                    if (!value) {
                        return "Masukkan alasan anda";
                    }
                }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const reason = result.value;
                        var div = $(this).closest('div');
                        div.html('please wait...');
                        handleDeclineButton(id,reason,div);
                    }
                });
        });
    });
</script>
@endpush
