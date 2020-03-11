<div class="dropdown is-hoverable">
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

@push('css')
  <style>
    .options-dropdown-trigger {
      display: inline-block;
      background: grey;
      width: 5px;
      height: 5px;
      border-radius: 50%;
      margin-bottom: 3px;
    }

    .options-dropdown-content {
      width: auto;
      max-width: 100px;
    }
    .options-dropdown-content i {
      margin-right: 3px;
    }
  </style>
@endpush