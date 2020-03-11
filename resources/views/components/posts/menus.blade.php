@component('components.options')
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
@endcomponent