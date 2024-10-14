<!DOCTYPE html>
<html>

<head>

@include('backend.dashboard.component.head')
</head>

<body>
    @csrf
    @if (session('error'))
    <div style="color: red;">
        {{ session('error') }}
    </div>
   @endif
    <div id="wrapper">
        @include('backend.dashboard.component.sidebar')
        <div id="page-wrapper" class="gray-bg">
        @include('Backend.dashboard.component.nav')
        @include($template)
        @include('backend.dashboard.component.footer')
        </div>
    </div>
    @include('backend.dashboard.component.script')
</body>
</html>
