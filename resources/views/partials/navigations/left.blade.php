<div id="left-nav" class="is-hidden-mobile">
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
        'href' => route('welcome'),
        'current' => (url()->current() === route('welcome') && app('request')->get('popular') !== '1')
      ],
      [
        'icon' => 'mdi mdi-star',
        'text' => 'Popular this week',
        'href' => route('welcome') . "?popular=1",
        'current' => (url()->current() === route('welcome') && app('request')->get('popular') === '1'),
      ],
      [
        'icon' => 'mdi mdi-folder-zip-outline',
        'text' => 'Saved Posts',
        'href' => route('user.saved_posts'),
        'current' => (route('user.saved_posts') === url()->current()),
      ],
    ];
    @endphp

    @foreach ($menus as $menu)
    <div class="left-nav-container">
      <a href="{{ $menu['href'] }}" class="
                                        @if (isset($menu['current']) && $menu['current'] === true) 
                                          current-menu 
                                        @endif
      ">
        <i class="{{ $menu['icon'] }} left-nav-icon"></i>
        <span>{{ $menu['text'] }}</span>
      </a>
    </div>
    @endforeach
  </div>
</div>