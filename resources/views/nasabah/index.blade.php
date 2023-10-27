@extends('layouts.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-black fw-bold">PILIH ANTRIAN LAYANAN</span></h4>

        @if ($countUser)
            <div class="row">
                <div class="col-md-6 col-xl-6">
                    <div class="card bg-primary text-white mb-3">
                        <div class="card-header">TELLER</div>
                        <div class="card-body">
                            <h5 class="card-title text-white" id="nomor-antrian-teller">No Antrian :
                                {{ $getMaxNoAntrianTeller ?? 0 }}
                            </h5>
                        </div>
                    </div>
                    {{-- jika sudah ada no antrian maka yang ditampilkan hanya cetak No Antrian saja --}}
                    @if ($countUserNoAntrianTeller == 0)
                        <a href="javascript:void(0)" class="btn btn-primary tombol-ambil" data-layanan="teller">AMBIL NO
                            ANTRIAN</a>
                    @else
                        <a href="{{ route('antrian.cetak-pdf-teller') }}" class="btn btn-primary mx-2" target="_blank">CETAK
                            NO ANTRIAN
                            SAYA</a>
                    @endif

                </div>
                <div class="col-md-6 col-xl-6">
                    <div class="card bg-secondary text-white mb-3">
                        <div class="card-header">CUSTOMER SERVICE (CS)</div>
                        <div class="card-body">
                            <h5 class="card-title text-white" id="nomor-antrian-cs">No Antrian :
                                {{ $getMaxNoAntrianCs ?? 0 }}
                            </h5>
                        </div>
                    </div>
                    @if ($countUserNoAntrianCs == 0)
                        <a href="javascript:void(0)" class="btn btn-secondary tombol-ambil" data-layanan="cs">AMBIL NO
                            ANTRIAN</a>
                    @else
                        <a href="{{ route('antrian.cetak-pdf-cs') }}" class="btn btn-secondary mx-2" target="_blank">CETAK
                            NO ANTRIAN SAYA</a>
                    @endif
                </div>
            </div>
        @else
            <div class="alert alert-danger" role="alert">SILAHKAN LENGKAPI / MASUKKAN DATA DIRI TERLEBIH DAHULU SEBELUM
                MEMILIH ANTRIAN LAYANAN ! <span>Klik
                    <a href="{{ route('nasabah.create') }}">Disini</a></span> Untuk Lengkapi Biodata</div>
        @endif

        <div class="row mt-5">
            <div class="col-md-6 col-xl-6">
                <div class="card bg-success text-white mb-3">
                    <div class="card-header text-center">NO ANTRIAN YANG SAAT INI DIPANGGIL OLEH LAYANAN TELLER</div>
                    <div class="card-body">
                        <h1 class="card-title text-white text-center" id="nomor-antrian-teller">
                            {{ $noAntrianTerakhirTeller ?? 0 }}
                        </h1>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-6">
                <div class="card bg-warning text-white mb-3">
                    <div class="card-header text-center">NO ANTRIAN YANG DIPANGGIL LAYANAN CUSTOMER SERVICE</div>
                    <div class="card-body">
                        <h1 class="card-title text-white text-center" id="nomor-antrian-teller">
                            {{ $noAntrianTerakhirCs ?? 0 }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {
            $('.tombol-ambil').click(function() {
                var layanan = $(this).data('layanan');

                $.ajax({
                    url: "{{ route('antrian.store') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        layanan: layanan
                    },
                    success: function(data) {

                        var nomorAntrian = data.nomor_antrian;
                        var message = data.message;
                        // Perbarui nomor antrian di tampilan
                        $('#nomor-antrian-' + layanan).text("No Antrian : " + nomorAntrian);
                        $('.tombol-ambil[data-layanan="' + layanan + '"]').hide();

                        Swal.fire('Sukses', message, 'success').then(function() {
                            window.location.reload();
                        });
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>
@endsection
