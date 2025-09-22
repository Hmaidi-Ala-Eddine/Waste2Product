<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin')</title>
    @stack('styles')
</head>
<body>
    @include('back.partials.topbar')
    <div class="admin-wrapper" style="display:flex;min-height:100vh;">
        @include('back.partials.sidebar')
        <main style="flex:1;padding:20px;">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
