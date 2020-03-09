{{-- Post Header --}}
<div id="post-header">
  {{-- Author Avatar --}}
  <img id="post-author-avatar" src="{{ $post->author->getAvatar() }}" alt="{{ $post['author']['username'] }}">
  {{-- Author --}}
  <span id="post-author-username">{{ $post->author->username }}</span>
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
      25
    </span>

    {{-- Options --}}
    @component('components.posts.options', ['post' => $post])
    @endcomponent
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