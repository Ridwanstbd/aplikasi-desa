<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
</head>
<body>
    <a id="redirect-link" href="{{ $url }}" target="_blank">Redirect</a>
        <script type="text/javascript">
            window.onload = function() {
                document.getElementById('redirect-link').click();
                window.location.href = '{{ route('home') }}';
            }
        </script>
    <p>Mengarahkan ke WhatsApp...</p>
</body>
</html>
