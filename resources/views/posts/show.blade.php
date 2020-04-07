@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="columns">
      {{-- Left bar --}}
      <div class="column is-3-tablet is-2-desktop hidden-mobile">
        @include('partials.navigations.left')
      </div>

      {{-- Content --}}
      <div id="main-content" class="column is-10-desktop is-9-widescreen is-8-fullhd">
        @php
          $auth = null;
          if (Auth::check()) {
            $auth = Auth::user();
            $auth['avatar'] = $auth->getAvatar();
          }
        @endphp

        {{-- Post Component --}}
        <post :post="{{ json_encode($post) }}" @auth :auth="{{ $auth }}" @endauth></post>

        {{-- Comments Section --}}
        <comment-section
          @auth 
            :auth="{{ $auth }}"
            post-author="{{ $post['author']['id'] === Auth::id() ? 'y' : 'n' }}" 
          @endauth
          post-id="{{ $post['id'] }}"
        ></comment-section>

        @guest
          <div class="has-text-centered title is-5" style="margin-top:30px; margin-bottom: 20px;">
            Please
            <a href="{{ route('login') }}" class="has-text-primary">Sign in</a>
            or
            <a href="{{ route('register') }}" class="has-text-primary">Sign up</a>
            participate in this conversation.
            </div>
        @endguest
      </div>
    </div>
  </div>
@endsection

@push('css')
  <style>
    @media screen and (min-width: 1216px) {
      #main-content {
        padding-left: 100px;
      }
    }
  </style>
@endpush

@push('js')
  <script>
    function incrementPostSeen() {
      const actionUrl = "{{ route('post.seen', ['id' => $post['id']]) }}";
      
      axios.post(actionUrl)
        .catch(err => {
          throw Error(err);
        });
    }

    function readNotif(notif_id) {
      axios.post(`/notifications/${notif_id}/mark-read`)
          .catch(() => ({}));
    }

    async function findNotif(notif_id) {
      try {
        const res = await axios.get(`/notifications/${notif_id}`)
        const { ok, data } = res.data;

        if (ok) {
          return data;
        }
      } catch (err) {
        return false;
      }
    }

    function isFromNotif() {
      const {search} = window.location;
      const urlParams = new URLSearchParams(search);

      const from = urlParams.get('from');
      const notif_id = urlParams.get('notif_id');

      if (from && from === 'notif' && notif_id) {
        readNotif(notif_id);
      }
    }

    window.addEventListener('load', () => {
      incrementPostSeen();
      isFromNotif();
    });
  </script>
@endpush