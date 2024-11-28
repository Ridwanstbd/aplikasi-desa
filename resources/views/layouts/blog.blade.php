<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ Storage::url($shop->logo_footer_url) }}">

    {{-- Dynamic Meta Tags for SEO --}}
    <title>
        @hasSection('meta_title')
            @yield('meta_title') - {{ $shop->name }}
        @else
            {{ $shop->name ?? 'Home' }}
        @endif
    </title>

    <meta name="description" content="@yield('meta_description', $shop->description ?? 'Temukan produk berkualitas di ' . ($shop->name ?? 'Toko Kami'))">
    <meta name="author" content="{{ $shop->name ?? config('app.name') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="@yield('meta_title', $shop->name ?? 'Home')">
    <meta property="og:description" content="@yield('meta_description', $shop->description ?? 'Temukan produk berkualitas di ' . ($shop->name ?? 'Toko Kami'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="@yield('meta_image', Storage::url($shop->logo_footer_url))">
    <meta property="og:site_name" content="{{ $shop->name ?? config('app.name') }}">

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('meta_title', $shop->name ?? 'Home')">
    <meta name="twitter:description" content="@yield('meta_description', $shop->description ?? 'Temukan produk berkualitas di ' . ($shop->name ?? 'Toko Kami'))">
    <meta name="twitter:image" content="@yield('meta_image', Storage::url($shop->logo_footer_url))">

    {{-- Schema.org Markup for Google --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "{{ $shop->name ?? config('app.name') }}",
        "url": "{{ url('/') }}",
        "logo": "{{ Storage::url($shop->logo_footer_url) }}"
    }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            padding-top: 3rem;
        }

        main {
            flex: 1;
        }

        footer {
            background: #f8f9fa;
            padding: 1rem;
        }
    </style>

    @stack('styles')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="font-sans text-gray-900 antialiased">
    @include('layouts.header')
        @yield('content')
    @include('layouts.footer')

    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module">
        function showAlert(icon, title, text){
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                confirmButtonText: 'Ok'
            });
        }
        @if (session('success'))
            showAlert('success','Sukses!','{{ session('success') }}')
        @endif
        @if (session('error'))
            @php $errorMessages = session('error'); @endphp
            @if (is_array($errorMessages))
                @foreach ($errorMessages as $errorMessage)
                    showAlert('error','Oops...','{{ $errorMessage }}')
                @endforeach
            @else
                    showAlert('error','Oops...','{{ $errorMessages }}')
            @endif
        @endif
    </script>
    <!-- sweetalert2 -->
    @stack('scripts')
</body>
</html>
