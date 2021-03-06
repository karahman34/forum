@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="columns">
            {{-- Left Bar --}}
            <div class="column is-2 hidden-mobile">
                @include('partials.navigations.left')
            </div>

            {{-- Main Content --}}
            <div class="column is-10" style="padding-top:0px !important;">
                {{-- Filter --}}
                <post-filter></post-filter>
                
                {{-- Post Card List --}}
                <div class="columns is-multiline">
                    @if (count($posts) < 1)
                        <div class="column is-12 has-text-centered title is-3 has-text-grey" style="padding-top:70px;">
                            <p>We can't find any posts.</p>
                            How about try with another keywords ?
                        </div>
                    @else
                        @foreach ($posts as $post)
                            {{-- Feed Card --}}
                            <div class="column is-half">
                                @component('components.posts.postCard', ['post' => $post])
                                @endcomponent
                            </div>
                        @endforeach

                        <div class="column is-12">
                            {{-- Pagination --}}
                            {{ $posts->onEachSide(4)->links('components.pagination') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection