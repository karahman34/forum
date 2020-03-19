{{-- Post Header --}}
<div id="post-header">
  {{-- Author Avatar --}}
  <img id="post-author-avatar" src="{{ $post->author->getAvatar() }}" alt="{{ $post['author']['username'] }}">
  {{-- Author --}}
  <a href="{{ route('user.profile', ['username' => $post->author->username]) }}" id="post-author-username">{{  $post->author->username }}</a>
  {{-- Post Circle divider --}}
  <span id="post-divider"></span>
  {{-- Post Created Time --}}
  <span>{{ $post->created_at->diffForHumans() }}</span>

  <div class="is-pulled-right" style="padding-top: 2px;">
    {{-- Seen Time Count --}}
    <span class="post-misc">
      <span class="mdi mdi-eye"></span>
      {{ $post->seen->count ?? 0 }}
    </span>

    {{-- Comment Count --}}
    <span class="post-misc">
      <span class="mdi mdi-comment-text-multiple"></span>
      {{ $post->comments_count }}
    </span>

    {{-- Options --}}
    <span class="is-pulled-right">
      @component('components.options', ['right' => true])
        <post-menus :post="{{ $post }}" @auth :auth="{{ auth()->user() }}" @endauth></post-menus>
      @endcomponent
    </span>
  </div>
</div>

{{-- Post Title --}}
<div id="post-title" class="is-capitalized">
  {{ $post['title'] }}
</div>

{{-- Post Body --}}
<div id="post-body">
  {{ $post['body'] }}
</div>

{{-- Attached Images --}}
@if (count($post->images) > 0)
  <p class="title is-5" style="margin-bottom: 12px;">Attached images</p>
  @foreach ($post->images as $image)
    <img src="{{ $image->getImage() }}" alt="{{ $image->getImage() }}" class="post-image">
  @endforeach
@endif

@push('js')
  <script>
    window.addEventListener('post-delete', ({ postId }) => {
      window.location.href = "/users/{{ $post->author->username }}";
    });
  </script>
@endpush