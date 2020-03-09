<div id="left-nav">
  <div id="left-nav-content">
    {{-- Create Button --}}
    <a 
      href="{{ route('post.create') }}" 
      class="button is-primary is-rounded" 
      style="margin-bottom: 15px;"
    >
      Create Post
    </a>

    @php
    $menus = [
      [
        'icon' => 'mdi mdi-map',
        'text' => 'All Posts',
      ],
      [
        'icon' => 'mdi mdi-star',
        'text' => 'Popular this week',
      ],
      [
        'icon' => 'mdi mdi-memory',
        'text' => 'Saved Posts',
      ],
    ];
    @endphp

    @foreach ($menus as $menu)
    <div class="left-nav-container">
      <span class="left-nav-icon">
        <i class="{{ $menu['icon'] }}"></i>
      </span>
      {{ $menu['text'] }}
    </div>
    @endforeach
  </div>
</div>