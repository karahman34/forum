@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="columns">
            {{-- Left Bar --}}
            <div class="column is-2">
                @include('partials.navigations.left')
            </div>

            {{-- Main Content --}}
            <div class="column is-10">
                <div class="columns is-multiline">
                    @foreach ($posts as $post)
                        {{-- Feed Card --}}
                        <div class="column is-half">
                            @component('components.posts.postCard', ['post' => $post])
                            @endcomponent
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection