@extends('layouts.admin')
@section('title', 'Settings')

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Account</div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="header-avatar" id="settingsAvatar" style="width: 48px; height: 48px; font-size: 1rem;">A</span>
                        <div>
                            <h2 class="h6 mb-0" id="settingsName">Admin</h2>
                            <p class="text-muted small mb-0" id="settingsEmail">—</p>
                        </div>
                    </div>
                    <p class="text-muted small mb-0">
                        Profile details are loaded from your signed-in session. Use this page for account preferences.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">Preferences</div>
                <div class="card-body">
                    <p class="text-muted small mb-0">More workspace settings will appear here.</p>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            try {
                const raw = localStorage.getItem('user');
                const user = raw ? JSON.parse(raw) : {};
                const name = user.name || user.full_name || user.email || 'Admin';
                const email = user.email || 'administrator';
                document.getElementById('settingsName').textContent = name;
                document.getElementById('settingsEmail').textContent = email;
                document.getElementById('settingsAvatar').textContent = String(name).trim().charAt(0).toUpperCase();
            } catch (e) {}
        </script>
    @endpush
@endsection
