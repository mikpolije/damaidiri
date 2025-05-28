<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Damai Diri">
        <meta name="keywords" content="Damai Diri">
        <meta name="author" content="mHaidar-Corp">
        
        <title>Damai Diri | {{ $page_title ?? '' }}</title>

        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
        
        <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/plugins/perfectscroll/perfect-scrollbar.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/plugins/pace/pace.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/plugins/highlight/styles/github-gist.css') }}" rel="stylesheet">

        <link href="{{ asset('assets/css/main.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

        @stack('css-internal')

        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/neptune.png') }}" />
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/neptune.png') }}" />
    </head>

    <body>
        <div class="app align-content-stretch d-flex flex-wrap">
            <x-molecules.sidebar />
            <div class="app-container">
                <x-molecules.navbar />
                <div class="app-content">
                    <div class="content-wrapper">
                        @if (auth()->user()->roles()->pluck('name')[0] == 'patient' && auth()->user()->profile == null)
                            <x-atoms.alert-notification status="warning" icon="done" label_title="Informasi" label_description="Silahkan lengkapi profil terlebih dahulu untuk membuka semua fitur dalam aplikasi" />
                        @endif
                        @if (auth()->user()->roles()->pluck('name')[0] == 'psychologist' && auth()->user()->psychologist == null)
                            <x-atoms.alert-notification status="warning" icon="done" label_title="Informasi" label_description="Silahkan lengkapi profil terlebih dahulu untuk membuka semua fitur dalam aplikasi" />
                        @endif
                        <div class="container-fluid">
                            <div class="example-container bg-white shadow">
                                <div class="example-content bg-white" style="background-image: linear-gradient( 55deg, hsl(0deg 0% 94%) 13%, hsl(344deg 0% 95%) 41%, hsl(344deg 0% 95%) 47%, hsl(344deg 0% 96%) 50%, hsl(344deg 0% 96%) 50%, hsl(344deg 0% 97%) 50%, hsl(344deg 0% 97%) 50%, hsl(344deg 0% 98%) 50%, hsl(344deg 0% 98%) 50%, hsl(344deg 0% 99%) 53%, hsl(344deg 0% 99%) 59%, hsl(0deg 0% 100%) 87% );">
                                    <div class="page-description d-flex align-items-center">
                                        <div class="page-description-content flex-grow-1">
                                            <h1>{{ $page_title ?? '' }}</h1>
                                            <span>{{ $page_description ?? '' }}</span>
                                        </div>
                                        <div class="page-description-actions">
                                            {{ $page_action ?? '' }}
                                        </div>
                                    </div>
                                    {{ $page_breadcrumb ?? '' }}
                                </div>
                            </div>
                            @if (session('success'))
                                <x-atoms.alert-notification status="success" icon="done" label_title="Berhasil" label_description="{!! session('success') !!}" />
                            @elseif (session('error'))
                                <x-atoms.alert-notification status="danger" icon="close" label_title="Terjadi Kesalahan" label_description="{!! session('error') !!}" />
                            @endif
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/plugins/jquery/jquery-3.5.1.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/perfectscroll/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/pace/pace.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/highlight/highlight.pack.js') }}"></script>
        <script src="{{ asset('assets/js/main.min.js') }}"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>

        @stack('js-internal')
    </body>
</html>