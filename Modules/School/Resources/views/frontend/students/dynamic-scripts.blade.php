@php
    $status = \Modules\School\Services\StudentService::prepareStatusFilter();
    \Log::debug($status);
    $firstStat = reset($status);
@endphp
<script>

    $(document).ready(function(){
        $(document).on('click', 'button.choose-student', function(e){
            var ele = $(this);
            var fireAjax = false;

            if(ele.hasClass('with-warning') && ele.hasClass('btn-danger')){
                Swal.fire({
                title: "PERHATIAN!!!",
                text: "Membatalkan alumni dengan status 'tidak tersedia' dapat membuat anda tidak bisa memilih alumni ini hingga statusnya 'tersedia' lagi setelah memuat ulang halaman ini.",
                type: "warning",
                showCancelButton: true,
                cancelButtonColor: "#dc3545",
                confirmButtonColor: "#a8a8a8",
                confirmButtonText: "Ya, saya ingin membatalkan alumni ini",
                closeOnConfirm: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        callPickStudent(ele);
                    }
                });
            }else{

                if(ele.hasClass('btn-danger')){
                    Swal.fire({
                        title: 'Hapus Data Booking ini?',
                        text: 'Data booking akan dihilangkan dari daftar. Anda masih bisa memilih alumni ini lagi nanti.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            callPickStudent(ele);
                        }
                    });
                }else{
                    Swal.fire({
                    title: "Tambahkan alumni ke daftar pilihan anda?",
                    text: "Kami akan memberitahu alumni tersebut untuk melakukan konfirmasi",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonColor: "#dc3545",
                    confirmButtonColor: "#4BB543",
                    confirmButtonText: "Yes",
                    closeOnConfirm: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            callPickStudent(ele);
                        }
                    });
                }
            }

        });

        function callPickStudent(ele){
                ele.attr("disabled", true);
                ele.html( 'please wait..');
                $.ajax({
                    type: "POST",
                    url: '{{route("frontend.bookings.pickStudent")}}',
                    data: {
                        "corporation_id" : "{{auth()->user()->corporation->id ?? 0}}",
                        "student_id" : ele.attr("data-id"),
                        "status" : "{{$firstStat}}",
                        "_method":"POST",
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (response) {
                    if(response.isPicked){
                        const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        showCloseButton: true,
                        timer: 10000,
                        timerProgressBar: true,
                        })

                        Toast.fire({
                        icon: 'success',
                        title: 'Alumni ditambahkan ke daftar anda. <a href="/bookings/list">Lihat Daftar..</a>'
                        })

                        ele.removeClass( 'btn-success');
                        ele.addClass( 'btn-danger');
                        ele.html( 'BATAL');

                        ele.attr("disabled", false);
                    }else{

                        const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        showCloseButton: true,
                        timer: 10000,
                        timerProgressBar: true,
                        })

                        Toast.fire({
                        icon: 'warning',
                        title: response.message
                        })

                        ele.removeClass( 'btn-danger');
                        ele.addClass( 'btn-success');
                        ele.html( 'PILIH');

                        ele.attr("disabled", false);
                    }
                    },
                    error: function (request, status, error,dudu) {
                        console.log("error nih");
                        console.log(request);
                        console.log(status);
                        console.log(error);
                        const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        showCloseButton: true,
                        timer: 10000,
                        timerProgressBar: true,
                        })

                        Toast.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan! Silakan coba beberapa saat lagi.'
                        })
                    }
                });
            }
    });
</script>
