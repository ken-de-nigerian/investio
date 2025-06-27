<a class="nav-link position-relative px-0" href="{{ route('logout.all') }}" id="logout-all-device-link" aria-label="Sign out of all sessions">
    <i class="fi-log-out fs-base me-2"></i>
    <span class="hover-effect-underline stretched-link">Sign out of all sessions</span>
</a>

<!-- Logout Form -->
<form action="{{ route('logout.all') }}" method="POST" id="logout-all-device-form" class="d-none">
    @csrf
</form>

<script>
    document.getElementById('logout-all-device-link').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('logout-all-device-form').submit();
    });
</script>
