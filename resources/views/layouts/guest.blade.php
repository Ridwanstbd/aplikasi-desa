<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ Storage::url($shop->logo_footer_url) }}">

    <title>{{ $shop->name ?? 'Home' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- Meta Pixel Code -->
    @if(!empty($shop->meta_pixel_id))
        <script>
            !function (f, b, e, v, n, t, s) { if (f.fbq) return; n = f.fbq = function () { n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments) }; if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0'; n.queue = []; t = b.createElement(e); t.async = !0; t.src = v; s = b.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t, s) }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js'); fbq('init', '{{$shop->meta_pixel_id}}'); fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{$shop->meta_pixel_id}}&ev=PageView&noscript=1" />
        </noscript>
    @endif
    <!-- End Meta Pixel Code -->
    <!-- TikTok Pixel Code -->
    @if($shop->tiktok_pixel_id)
        <script>
            !function (w, d, t) {
                w.TiktokAnalyticsObject = t;
                var ttq = w[t] = w[t] || [];
                ttq.methods = ["page", "track", "identify", "instances", "debug", "on", "off", "once", "ready", "alias", "group", "enableCookie", "disableCookie"], ttq.setAndDefer = function (t, e) {
                    t[e] = function () {
                        t.push([e].concat(Array.prototype.slice.call(arguments, 0)))
                    }
                };
                for (var i = 0; i < ttq.methods.length; i++) ttq.setAndDefer(ttq, ttq.methods[i]);
                ttq.instance = function (t) {
                    for (var e = ttq._i[t] || [], n = 0; n < ttq.methods.length; n++
                    )ttq.setAndDefer(e, ttq.methods[n]);
                    return e
                }, ttq.load = function (e, n) {
                    var i = "https://analytics.tiktok.com/i18n/pixel/events.js";
                    ttq._i = ttq._i || {}, ttq._i[e] = [], ttq._i[e]._u = i, ttq._t = ttq._t || {}, ttq._t[e] = +new Date, ttq._o = ttq._o || {}, ttq._o[e] = n || {};
                    n = document.createElement("script");
                    n.type = "text/javascript", n.async = !0, n.src = i;
                    var a = document.getElementsByTagName("script")[0];
                    a.parentNode.insertBefore(n, a)
                };

                ttq.load('{{ $shop->tiktok_pixel_id }}');
                ttq.page();
            }(window, document, 'ttq');
        </script>
    @endif
    <!-- End TikTok Pixel Code -->
    <!-- Google Tag Manager -->
    @if(!empty($shop->google_tag_id))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{$shop->google_tag_id}}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', '{{$shop->google_tag_id}}');
        </script>
    @endif
    <!-- End Google Tag Manager -->
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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="font-sans text-gray-900 antialiased">
    @include('layouts.header')
    <main>
        @yield('content')
    </main>
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
