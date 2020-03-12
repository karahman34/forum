<nav id="top-nav" class="navbar" role="navigation" aria-label="main navigation">
  <div class="container">
    {{-- Navbar Brand --}}
    <div class="navbar-brand">
      {{-- The Brand --}}
      <a class="navbar-item" href="/">
        <img src="https://bulma.io/images/bulma-logo.png" width="112" height="28">
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
        @if (!Auth::check())
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
        @else
          {{-- Home --}}
          <a href="/" class="navbar-item">
            Home
          </a>

          {{-- Authorize Menus --}}
          <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link dropdown-toggle">
              {{ Auth::user()->username }}
            </a>

            {{-- Dropdown Structure --}}
            <div class="navbar-dropdown is-right">
              <a class="navbar-item" onclick="logoutHandler()">
                <form id="logout-form" method="POST" action="{{route('logout')}}">
                  @csrf
                </form>
                Logout
              </a>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</nav>

<script>
  function logoutHandler() {
    const logoutForm = document.getElementById('logout-form');
    logoutForm.submit();
  }

  function toggleBurger() {
    let navBurgerActive = false;
    const navBurger = document.querySelector('.navbar-burger.burger');
    const navBarMenu = document.querySelector('.navbar-menu');
    navBurger.addEventListener('click', () => {
      if (!navBurgerActive) {
        navBurgerActive = true;

        navBurger.classList.add('is-active');
        navBarMenu.classList.add('is-active');
      } else {
        navBurgerActive = false;

        navBurger.classList.remove('is-active');
        navBarMenu.classList.remove('is-active');
      }
    });
  }

  toggleBurger();
</script>