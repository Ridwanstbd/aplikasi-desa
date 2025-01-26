<sidebar class="d-flex flex-column flex-shrink-0 ">
    <div class="d-flex container justify-content-between">
        <button class="navbar-toggler d-lg-none d-block" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list navbar-toggler-icon" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
            </svg>
        </button>
    </div>
    <div class="collapse navbar-collapse min-vh-100" id="navbarSupportedContent">
        <ul class="nav nav-pills flex-column mb-auto mt-4">
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle text-black" href="#" role="button" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                {{ __('Edit Profile') }}
                </a>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>

</sidebar>
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navbarSupportedContent = document.getElementById('navbarSupportedContent');
        function checkScreenWidth() {
            if (window.innerWidth >= 768) {
                navbarSupportedContent.classList.add('show');
            }
        }
        checkScreenWidth();
        window.addEventListener('resize', checkScreenWidth);
    });
</script>
@endpush
