<base href="{{ config('app.url') }}">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>LARAVEL E-COMMERCE</title>

<link href="{{ asset('giaodien/css/bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('giaodien/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('giaodien/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
<link href="{{ asset('giaodien/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('giaodien/css/animate.css') }}" rel="stylesheet">
<link href="{{ asset('giaodien/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('giaodien/css/sofm.css') }}" rel="stylesheet">
<link href="{{ asset('giaodien/css/plugins/switchery/switchery.css') }}" rel="stylesheet">
<link href="{{ asset('giaodien/plugin/nice-select/css/nice-select.css') }}" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.16.6/dist/css/uikit.min.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<script>
    var BASE_URL = '{{ secure_url(config('app.url')) }}';
</script>
