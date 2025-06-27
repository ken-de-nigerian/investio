<a class="dropdown-item" href="{{ route('logout.current', $session['id']) }}" id="logout-one-device-link-{{ $session['id'] }}" aria-label="Sign out this device">
    <i class="fi-log-out opacity-75 me-2"></i>
    Sign out
</a>

<!-- Logout Form -->
<form action="{{ route('logout.current', $session['id']) }}" method="POST" id="logout-one-device-form-{{ $session['id'] }}" class="d-none">
    @csrf
</form>

<script>
    document.getElementById('logout-one-device-link-{{ $session['id'] }}').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('logout-one-device-form-{{ $session['id'] }}').submit();
    });
</script>
