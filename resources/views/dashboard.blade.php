@extends('layouts.master')

@section('dashboard', '')
@section('anggota', 'collapsed')

@section('content')
    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">

                    <!-- Nasabah Card -->
                    <div class="col-xxl-4 col-md-6 col-lg-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Nasabah</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($item['nasabah'], 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Nasabah Card -->

                    <!-- Simpanan Card -->
                    <div class="col-xxl-4 col-md-6 col-lg-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Simpanan</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-piggy-bank"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($item['simpanan'], 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Simpanan Card -->

                    <!-- Pinjaman Card -->
                    <div class="col-xxl-4 col-md-6 col-lg-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Pinjaman</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($item['pinjaman'], 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Pinjaman Card -->

                </div>
            </div><!-- End Left side columns -->
        </div>
    </section>
@endsection
