<div class="card">
  <div class="card-content">
    <div class="post-card-header">
      {{-- User Image --}}
      <img src="{{ $post->author->getAvatar() }}" class="post-card-avatar">

      {{-- Username --}}
      <a href="{{ route('user.profile', ['username' => $post->author->username]) }}" class="post-card-username">{{  $post->author->username }}</a>

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
        <span>
          @if ($post->seens_count && $post->seens_count >= 0)
            {{ $post->seens_count }}
          @else
            {{ $post->seen->count ?? 0 }}
          @endif
        </span>
      </span>

      {{-- Comments Count --}}
      <span class="post-card-misc">
        <i class="mdi mdi-comment-text-multiple"></i>
        <span>{{ $post->comments_count ?? 0 }}</span>
      </span>

      {{-- Options --}}
      <span class="is-pulled-right">
        @component('components.posts.menus', ['post' => $post])
        @endcomponent
      </span>
    </div>
  </div>
</div>