<div class="dropdown is-hoverable">
  {{-- The Dots Trigger --}}
  <div class="dropdown-trigger">
    <span class="post-dropdown-trigger"></span>
    <span class="post-dropdown-trigger"></span>
    <span class="post-dropdown-trigger"></span>
  </div>

  {{-- Dropdown Structure --}}
  <div class="dropdown-menu">
    <div class="dropdown-content post-dropdown-content" role="menu">
      @auth
        @can('update', $post)
          {{-- Edit --}}
          <a class="dropdown-item" href="{{ route('post.edit', ['id' => $post->id]) }}">
            <i class="mdi mdi-pencil"></i>
            Edit
          </a>
        @endcan
      @endauth
      {{-- Save --}}
      <a href="#" class="dropdown-item">
        <i class="mdi mdi-folder-zip"></i>
        Save
      </a>
    </div>
  </div>
</div>