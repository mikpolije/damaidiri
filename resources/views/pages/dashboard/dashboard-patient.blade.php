<x-app-layout>
    @push('css-internal')
        <style>
            .custom-hover:hover {
                background-color: #f1f1f1;
                transition: background-color 0.3s ease;
            }
        </style>
    @endpush

    <x-slot name="page_title"> Dasbor </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="waving_hand" description="Selamat Datang <b><i>{{ auth()->user()->name }}!</i></b>, di Dasbor <b>Damai Diri.</b> Solusi untuk kesehatan mental anda." />
    </x-slot>
    @canany(['my_account', 'my_profile', 'my_password'])
    <x-slot name="page_action">
        <x-atoms.pure-button-redirect class="btn-success" icon="account_circle" label="Profil Saya" route="profile.my-account" />
    </x-slot>
    @endcanany
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('dashboard.patient')],
            ['name' => 'Damai Diri.', 'url' => route('dashboard.patient')],
        ]" />
    </x-slot>
    
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex pt-1">
                        <div class="widget-stats-icon widget-stats-icon-primary">
                            <i class="material-icons-outlined">assignment</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title">Tes Tersedia</span>
                            <span class="widget-stats-amount">{{ $count_questionnaire }}</span>
                        </div>
                        <a href="{{ route('questionnaire-screening.list') }}" class="widget-stats-indicator align-self-center border">
                            <i class="material-icons">visibility</i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex pt-1">
                        <div class="widget-stats-icon widget-stats-icon-danger">
                            <i class="material-icons-outlined">auto_stories</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title">Jurnal Tersedia</span>
                            <span class="widget-stats-amount">{{ $count_journal }}</span>
                        </div>
                        <a href="{{ route('journal-response.list') }}" class="widget-stats-indicator align-self-center border">
                            <i class="material-icons">visibility</i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex pt-1">
                        <div class="widget-stats-icon widget-stats-icon-warning">
                            <i class="material-icons-outlined">auto_stories</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title">Riwayat Tes</span>
                            <span class="widget-stats-amount">{{ $count_history_questionnaire }}</span>
                        </div>
                        <a href="{{ route('history-screening.list') }}" class="widget-stats-indicator align-self-center border">
                            <i class="material-icons">visibility</i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex pt-1">
                        <div class="widget-stats-icon widget-stats-icon-success">
                            <i class="material-icons-outlined">auto_stories</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title">Riwayat Jurnal</span>
                            <span class="widget-stats-amount">{{ $count_history_journal }}</span>
                        </div>
                        <a href="{{ route('history-response.list') }}" class="widget-stats-indicator align-self-center border">
                            <i class="material-icons">visibility</i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card widget widget-list">
                <div class="card-header">
                    <h5 class="card-title">Statistik.</h5>
                </div>
                <div class="card-body">
                    <canvas id="statistic_chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->profile != null)
        <div class="row">
            <div class="col-md-12">
                <div class="card widget widget-list">
                    <div class="card-header">
                        <h5 class="card-title">
                            Daftar Mitra Kami.
                            <span class="badge badge-info badge-style-light">Total : {{ $data_partner->count() }} Mitra</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="widget-list-content list-unstyled">
                            @php
                                $sorted_partner = $data_partner->sortByDesc(function ($item) {
                                    return $item->regency->province->id == auth()->user()->profile->regency->province->id;
                                });
                            @endphp
                            @forelse ($sorted_partner as $item)
                                <li class="widget-list-item widget-list-item-blue p-4 custom-hover">
                                    <span class="widget-list-item-avatar">
                                        <div class="avatar avatar-rounded">
                                            <div class="avatar-title">{{ get_initial($item->name) }}</div>
                                        </div>
                                    </span>
                                    <span class="widget-list-item-description">
                                        <span class="fw-bold">
                                            {{ $item->name }}
                                            @if ($item->regency->province->id == auth()->user()->profile->regency->province->id)
                                                (Terdekat dari anda)
                                            @endif
                                        </span>
                                        <br />
                                        <span class="widget-list-item-description-date">
                                            {{ $item->address }}
                                        </span>
                                    </span>
                                    <div class="widget-list-item-transaction-amount-positive">
                                        <a href="{{ $item->google_maps_url }}" target="_blank">
                                            <button type="button" class="btn btn-primary btn-small btn-burger">
                                                <i class="material-icons-outlined" style="font-size: 10pt;">map</i>
                                            </button>
                                        </a>
                                        &nbsp;
                                        <a href="https://api.whatsapp.com/send/?phone={{ $item->phone_number }}&text={{ urlencode('Halo, saya ' . auth()->user()->name . ' pengguna aplikasi Damai Diri ingin melakukan konsultasi.') }}&type=phone_number&app_absent=0" target="_blank">
                                            <button type="button" class="btn btn-success btn-small btn-burger">
                                                <i class="material-icons-outlined" style="font-size: 10pt;">chat</i>
                                            </button>
                                        </a>
                                    </div>
                                </li>
                            @empty
                                <span>Belum ada Mitra yang ditambahkan.</span>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('js-internal')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js" integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
        <script>
            const ctx = document.getElementById('statistic_chart');
            const dataOverview = @json($data_overview);

            const datasets = dataOverview.map(item => ({
                label: item.title,
                data: item.screening_avg,
                borderWidth: 2,
                tension: 0.3,
                fill: false
            }));

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ],
                    datasets: datasets
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>