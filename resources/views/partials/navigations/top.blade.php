<nav id="top-nav" class="navbar" role="navigation" aria-label="main navigation">
  <div class="container">
    {{-- Navbar Brand --}}
    <div class="navbar-brand">
      {{-- The Brand --}}
      <a class="navbar-item" href="/">
        {{-- <img src="https://bulma.io/images/bulma-logo.png" width="112" height="28"> --}}
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
          {{-- Special menu only on mobile --}}
          <span class="is-inline-mobile is-hidden-tablet">
            {{-- Popular --}}
            <a href="{{ route('welcome') }}" class="navbar-item">Popular this week</a>
            {{-- Saved Posts --}}
            <a href="{{ route('user.saved_posts') }}" class="navbar-item">Saved Posts</a>
          </span>

          {{-- Dropdown --}}
          <div class="navbar-item has-dropdown is-hoverable">
            {{-- Dropdown Activator --}}
            <a class="navbar-link dropdown-toggle">
              {{ Auth::user()->username }}
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