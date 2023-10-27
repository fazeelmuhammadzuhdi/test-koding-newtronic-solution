@extends('layouts.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data Diri Nasabah</span>
        </h4>

        <!-- Basic Layout & Basic with Icons -->
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Lengkapi Data Diri Nasabah</h5>
                        <small class="text-muted float-end">Input Information Data Nasabah</small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('nasabah.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" name="nama"
                                        class="form-control @error('nama') is-invalid @enderror" id="basic-default-name"
                                        placeholder="Contoh : Anto" value="{{ old('nama', $authUser?->nama) }}" autofocus />
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label" for="basic-default-name">No Telepon</label>
                                <div class="col-sm-10">
                                    <input type="text" name="telepon"
                                        class="form-control @error('telepon') is-invalid @enderror" id="basic-default-name"
                                        placeholder="Contoh : 08121" value="{{ old('telepon', $authUser?->telepon) }}"
                                        autofocus />
                                    @error('telepon')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Save Data Diri</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
