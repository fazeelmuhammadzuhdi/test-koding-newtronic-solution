@extends('layouts.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold"><span class="text-black fw-bold text-primary">Urutan Nomor Antrian
                {{ ucwords(auth()->user()->roles) }}</span>
        </h4>
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Nomor Antrian Di Panggil Saat Ini</h5>
                        <h1 class="card-text" id="nomor-antrian">{{ $noAntrianTerakhir ?? 0 }}</h1>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Yang Sudah Di Panggil</h5>
                        <h1 class="card-text" id="nomor-antrian">{{ $jumlahAntrianDipanggil ?? 0 }}</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <h5 class="card-header">Panggil Antrian Nasabah Berdasarkan Nomor Antrian Terkecil </h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Nasabah</th>
                            <th>Nomor Antrian</th>
                            <th>Status</th>
                            <th>No Telepon</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($nasabahTeller as $item)
                            <tr>
                                <td>{{ $item->nasabah->nama }}</td>
                                <td><span class="badge badge-center rounded-pill bg-primary">{{ $item->no_antrian }}</span>
                                <td>{!! $item->status_teks !!}</td>
                                <td>{{ $item->nasabah->telepon }}</td>
                                <td>
                                    <button class="btn btn-primary panggil-btn"
                                        data-antrian-id="{{ $item->id }}">Panggil
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr class="text-center fw-bold fs-5">
                                <td colspan="5">No Data</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>



        <div class="card mt-3">


            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $title }}</h5>
                <label class="float-end fw-bold">
                    Total Nasabah :
                    <span class="badge bg-info rounded-pill">{{ $daftarAntrian->total() }}</span>
                </label>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Nasabah</th>
                            <th>No Antrian</th>
                            <th>Status</th>
                            <th>No Telepon</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($daftarAntrian as $item)
                            <tr>
                                <td>{{ $loop->iteration + $daftarAntrian->firstItem() - 1 . '.' }}</td>
                                <td>{{ $item->nasabah->nama }}</td>
                                <td><span class="badge badge-center rounded-pill bg-primary">{{ $item->no_antrian }}</span>
                                </td>
                                <td>{!! $item->status_teks !!}</td>
                                <td>{{ $item->nasabah->telepon }}</td>
                            </tr>
                        @empty
                            <tr class="text-center fw-bold fs-5">
                                <td colspan="5">No Data</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="container mt-3">
                <div class="row">
                    <div class="col-md-12">
                        {{ $daftarAntrian->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.panggil-btn').click(function() {
                var antrianId = $(this).data('antrian-id');
                $.ajax({
                    url: "{{ route('antrian.panggil') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        antrian_id: antrianId
                    },
                    success: function(data) {
                        var nomorAntrian = data.nomor_antrian;
                        var message = data.message;
                        $('#nomor-antrian').text(nomorAntrian);
                        Swal.fire('Sukses', message, 'success').then(function() {
                            window.location.reload();
                        })
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>
@endsection
