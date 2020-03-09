<div class="card">
  <div class="card-content">
    <div class="post-card-header">
      {{-- User Image --}}
      <img src="{{ $post->author->getAvatar() }}" class="post-card-avatar">

      {{-- Username --}}
      <span class="post-card-username">{{ $post->author->username }}</span>

      {{-- Dot --}}
      <span class="post-card-username-image-divider"></span>

      {{-- Created Time  --}}
      <span>{{ $post->created_at->diffForHumans() }}</span>
    </div>

    {{-- Title --}}
    <a href="{{ route('post.show', ['id' => $post->id]) }}" class="post-card-title">
      {{ $post->title }}
    </a>

    {{-- Tags --}}
    @foreach ($post->tags->pluck('name') as $tag)
      <span class="tag is-medium post-card-tag">{{ $tag }}</span>
    @endforeach

    {{-- Post Misc --}}
    <div class="post-card-misc-container">
      {{-- Seen Count --}}
      <span class="post-card-misc">
        <i class="mdi mdi-eye"></i>
        <span>131</span>
      </span>

      {{-- Comments Count --}}
      <span class="post-card-misc">
        <i class="mdi mdi-comment-text-multiple"></i>
        <span>31</span>
      </span>

      {{-- Options --}}
      <div class="dropdown is-hoverable is-pulled-right">
        {{-- The Dots Trigger --}}
        <div class="dropdown-trigger">
            <span class="post-card-misc">
              <span class="options"></span>
              <span class="options"></span>
              <span class="options"></span>
            </span>
        </div>

        {{-- Dropdown Structure --}}
        <div class="dropdown-menu">
          <div class="dropdown-content">
            <a href="#" class="dropdown-item">
              Save
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>