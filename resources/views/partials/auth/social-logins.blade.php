@if($social_login)
    <!-- Divider -->
    <div class="d-flex align-items-center my-4">
        <hr class="w-100 m-0">
        <span class="text-body-emphasis fw-medium text-nowrap mx-4">or continue with</span>
        <hr class="w-100 m-0">
    </div>

    <!-- Social login -->
    <div class="d-flex flex-sm-row gap-3 pb-4 mb-3 mb-lg-4">
        <a href="{{ route('social.redirect', 'google') }}" class="btn btn-lg btn-outline-secondary w-100 px-2">
            <i class="bi bi-google ms-1 me-1"></i>
            Google
        </a>

        <a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-lg btn-outline-secondary w-100 px-2">
            <i class="bi bi-facebook ms-1 me-1"></i>
            Facebook
        </a>
    </div>
@endif
