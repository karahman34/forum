<div class="dropdown is-hoverable @isset($right) is-right @endisset">
  {{-- The Dots Trigger --}}
  <div class="dropdown-trigger">
    <span class="options-dropdown-trigger"></span>
    <span class="options-dropdown-trigger"></span>
    <span class="options-dropdown-trigger"></span>
  </div>

  {{-- Dropdown Structure --}}
  <div class="dropdown-menu">
    <div class="dropdown-content options-dropdown-content" role="menu">
      {{ $slot }}
    </div>
  </div>
</div>