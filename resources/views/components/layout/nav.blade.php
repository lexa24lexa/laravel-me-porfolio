<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-menu-main" id="navMenu">
            <div class="navbar-start">
                <a href="{{ route('welcome') }}"
                   class="navbar-item-left {{ Request::route()->getName() === 'welcome' ? 'is-active' : '' }}">
                    <img src="{{ asset('image/ASlogo.png') }}" class="logo" alt="Logo">
                </a>
            </div>
            <div class="navbar-end">
                <a href="{{ route('work') }}"
                   class="navbar-item-right {{ Request::route()->getName() === 'work' ? 'is-active' : '' }}">Work
                </a>
                <a href="{{ route('research') }}"
                   class="navbar-item-right {{ Request::route()->getName() === 'research' ? 'is-active' : '' }}">Research
                </a>
                <a href="{{ route('contacts') }}"
                   class="navbar-item-right {{ Request::route()->getName() === 'contacts' ? 'is-active' : '' }}">Contacts
                </a>
                <a href="https://www.linkedin.com/in/alexandra-smirnova2406/"
                   class="navbar-item-left" target="_blank">
                    <img src="{{ asset('image/Linkedin icon.png') }}" class="linkedin" alt="LinkedIn logo">
                </a>
            </div>
        </div>
    </div>
</nav>
