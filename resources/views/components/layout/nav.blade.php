<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-menu-main">
            <a href="{{ route('welcome') }}" class="navbar-item">
                <img src="{{ asset('image/ASlogo.png') }}" class="logo" alt="Logo">
            </a>
            <a href="{{ route('me') }}" class="navbar-item">Who am I?</a>
            <a href="{{ route('work') }}" class="navbar-item">Work</a>
            <a href="{{ route('contacts') }}" class="navbar-item">Contacts</a>
            <a href="https://www.linkedin.com/in/alexandra-smirnova2406/" class="navbar-item" target="_blank">
                <img src="{{ asset('image/Linkedin icon.png') }}" class="linkedin" alt="LinkedIn logo">
            </a>
            <a href="https://github.com/lexa24lexa" class="navbar-item" target="_blank">
                <img src="{{ asset('image/githubLogo.png') }}" class="github" alt="Github logo">
            </a>
            @auth
            <span class="navbar-item">Hello, {{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="navbar-item">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="navbar-item">Login</a>
            @endauth
        </div>
    </div>
</nav>
