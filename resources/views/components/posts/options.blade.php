<div class="dropdown is-hoverable is-pulled-right">
  {{-- The Dots Trigger --}}
  <div class="dropdown-trigger">
    <span class="post-dropdown-trigger"></span>
    <span class="post-dropdown-trigger"></span>
    <span class="post-dropdown-trigger"></span>
  </div>

  {{-- Dropdown Structure --}}
  <div class="dropdown-menu">
    <div class="dropdown-content">
      {{-- Edit --}}
      @auth
        @can('update', $post)
          <i class="mdi mdi-pencil"></i>
          <a href="{{ route('post.edit', ['id' => $post->id]) }}">Edit</a>
        @endcan
      @endauth
      <a href="#" class="dropdown-item">
        Save
      </a>
    </div>
  </div>
</div>