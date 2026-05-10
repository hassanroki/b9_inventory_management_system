<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Admin')</title>

    <!-- Bootstrap 5 CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    {{-- Data Table CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css" />
    <!-- Admin layout CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main content area -->
        <main class="admin-main">
            {{-- Header  --}}
            @include('layouts.partials.header')

            <div class="admin-content">
                @yield('content')
            </div>

        </main>

    </div>

    {{-- Axios --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Bootstrap 5 JS (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- jQuery CDN --}}
    <script src="
            https://cdn.jsdelivr.net/npm/jquery@4.0.0/dist/jquery.min.js
            "></script>
    {{-- Data Table JS --}}
    <script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
    {{-- Common JS --}}
    <script src="{{ asset('js/common.js') }}"></script>
    <!-- Admin layout JS -->
    <script src="{{ asset('js/admin.js') }}"></script>
    @stack('scripts')
</body>

</html>
