{{-- Post Header --}}
<div id="post-header">
  {{-- Author Avatar --}}
  <img id="post-author-avatar" src="{{ $post['author']['avatar'] }}" alt="{{ $post['author']['username'] }}">
  {{-- Author --}}
  <span id="post-author-username">{{ $post['author']['username'] }}</span>
  {{-- Post Circle divider --}}
  <span id="post-divider"></span>
  {{-- Post Created Time --}}
  <span>{{ $post['created_at'] }}</span>

  <div class="is-pulled-right" style="padding-top: 2px;">
    {{-- Seen Time Count --}}
    <span class="post-misc">
      <span class="mdi mdi-eye"></span>
      200
    </span>

    {{-- Comment Count --}}
    <span class="post-misc">
      <span class="mdi mdi-comment-text-multiple"></span>
      25
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
<p class="title is-5" style="margin-bottom: 12px;">Attached images</p>
@foreach ($post['images'] as $image)
  <img src="{{ $image }}" alt="{{ $image }}" class="post-image">
@endforeach