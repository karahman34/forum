<nav id="top-nav" class="navbar" role="navigation" aria-label="main navigation">
  <div class="container">
    {{-- Navbar Brand --}}
    <div class="navbar-brand">
      {{-- The Brand --}}
      <a class="navbar-item" href="/">
        <span class="has-text-weight-semibold">Diskusi</span>
        <span id="kuy">Kuy!</span>
      </a>

      {{-- Nav Burger --}}
      <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="app-top-nav">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>

    {{-- Navbar Menus --}}
    <div id="app-top-nav" class="navbar-menu">
      {{-- Right Menus --}}
      <div class="navbar-end">
        {{-- Unauthorize Menus --}}
        @guest
          <div class="navbar-item">
            <div class="buttons">
              <a href="{{ route('register') }}" class="button is-primary">
                <strong>Sign up</strong>
              </a>
              <a href="{{ route('login') }}" class="button is-light">
                Log in
              </a>
            </div>
          </div>
        @endguest

        {{-- Authorize Menus --}}
        @auth
          {{-- Search --}}
          <search-navbar></search-navbar>

          {{-- Notification Count --}}
          <notification-count :auth="{{ auth()->user() }}"></notification-count>

          {{-- Special menu only on mobile --}}
          <span class="is-inline-mobile is-hidden-tablet">
            {{-- Popular --}}
            <a href="{{ route('welcome') }}" class="navbar-item">
              <i class="mdi mdi-star"></i>
              <span>Popular this week</span>  
            </a>
            {{-- Saved Posts --}}
            <a href="{{ route('user.saved_posts') }}" class="navbar-item">
              <i class="mdi mdi-folder-zip-outline"></i>
              Saved Posts
            </a>
          </span>

          {{-- Dropdown --}}
          <div class="navbar-item has-dropdown is-hoverable">
            {{-- Dropdown Activator --}}
            <a class="navbar-link dropdown-toggle">
              <img id="user-avatar" src="{{ Auth::user()->getAvatar() }}" style="vertical-align: middle">
              <span>{{ Auth::user()->username }}</span>
            </a>

            {{-- Dropdown Structure --}}
            <div class="navbar-dropdown is-right">
              {{-- Profile --}}
              <a class="navbar-item" href="{{ route('user.profile', ['username' => Auth::user()->username]) }}">Profile</a>
              {{-- Password --}}
              <a class="navbar-item" href="{{ route('user.edit_password', ['username' => Auth::user()->username]) }}">Password</a>
              {{-- Logout --}}
              <logout></logout>
            </div>
          </div>
        @endauth
      </div>
    </div>
  </div>
</nav>