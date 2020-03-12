<div id="profile-left-nav-container">
  <div id="user-detail-container" class="columns is-mobile is-multiline">
    <div class="column is-4-mobile is-12-tablet">
      {{-- Avatar --}}
      <img src="{{ $user->getAvatar() }}" alt="{{ $user->getAvatar() }}" id="user-avatar">
    </div>

    <div class="column is-8-mobile is-12-tablet no-y-padding">
      {{-- Username --}}
      <p id="user-username" class="title is-4">{{ "@" . $user->username }}</p>

      {{-- Joined On --}}
      <span class="is-hidden-tablet is-inline-block-mobile">
        <p class="has-text-grey">Joined since:</p>
        <span class="title is-6">{{ $user->created_at->format('F Y') }}</span>
      </span>
    </div>

    <div class="column is-hidden-mobile is-12-tablet no-y-padding" style="margin-bottom: 10px;">
      {{-- Joined On --}}
      <p class="has-text-grey">Joined since:</p>
      <span class="title is-6">{{ $user->created_at->format('F Y') }}</span>
    </div>
  </div>

  @auth
    @if (Auth::user()->username === $user->username)
      {{-- Edit Profile Button --}}
      <a href="{{ route('user.edit', ['username' => $user->username]) }}" id="edit-profile-button" class="button">
        Edit Profile
      </a>
    @endif
  @endauth
</div>

@push('css')
  <style>
    #user-avatar {
      height: 110px;
      object-fit: cover;
    }

    @media screen and (min-width: 768px) {
      #user-avatar {
        height: 180px;
      }
    }

    @media screen and (min-width: 1408px) {
      #user-avatar {
        height: 204px;
      }
    }

    #profile-left-nav-container {
      position: sticky;
      top: 75px;
    }

    #user-detail-container {
      margin-bottom: 0px !important;
    }

    @media screen and (max-width: 768px) {
      #user-avatar {
        width: 100%;
      }
    }

    @media screen and (min-width: 768px) {
      .no-y-padding {
        padding-top: 0px !important;
        padding-bottom: 0px !important;
      }
    }

    #edit-profile-button {
      width: 100%;
    }
  </style>
@endpush
