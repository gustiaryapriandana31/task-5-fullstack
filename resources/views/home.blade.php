@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>{{ __('Dashboard') }}</h1>
            
            <form action="/home">
                @if(request("category"))
                    <input type="hidden" name="category" value="{{ request("category") }}">
                @endif
                @if(request("user"))
                    <input type="hidden" name="user" value="{{ request("user") }}">
                @endif
            </form>

            @if ($posts->count())
            <div class="card mb-3">
                <div style="max-height: 450px; overflow:hidden;">
                    <img src="{{ asset($posts[0]->image) }}" class="card-img-top img-fluid" alt="{{ $posts[0]->category->name }}">
                </div>
               
                <div class="card-body text-center">
                    <h2 class="card-title"><a href="/post/{{ $posts[0]->id }}" class="text-decoration-none text-dark">{{ $posts[0]->title }}</a></h2>
                    <p>
                        <small class="text-muted">
                        By : <a href="/home?user={{ $posts[0]->user->name }}" class="text-decoration-none">{{ $posts[0]->user->name }}</a> in <a href="/home?category={{ $posts[0]->category->id }}" class="text-decoration-none">{{ $posts[0]->category->name }}</a> {{ $posts[0]->created_at->diffForHumans() }}
                        <small>
                    </p>
                    <p class="card-text">{{ $posts[0]->content }}</p>
    
                    <a href="/post/{{ $posts[0]->id }}" class="text-decoration-none btn btn-primary">Read more</a>
                </div>
            </div>    
       
        {{-- // card untuk postingan yang lain --}}
        <div class="container">
            <div class="row">
                @foreach ($posts->skip(1) as $post)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="position-absolute px-3 py-2" style="background-color: rgba(0, 0, 0, 0.6)">
                                <a href="/home?category={{ $post->category->id }}" class="text-decoration-none text-white">{{ $post->category->name }}</a>
                            </div>
                            <img src="{{ asset($post->image) }}" style="max-height: 500px;" class="card-img-top img-fluid" alt="{{ $post->category->name }}">

                            <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p>
                                <small class="text-muted">
                                    By : <a href="/home?user={{ $post->user->name }}" class="text-decoration-none">{{ $post->user->name }}</a> {{ $post->created_at->diffForHumans() }}
                                </small>
                            </p>
                            <p class="card-text">{{ $post->content }}</p>
                            <a href="/post/{{ $post->id }}" class="btn btn-primary">Read more</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    
        @else
            <p class="text-center fs-4">No Post Found</p>   
        @endif

        
        <div class="d-flex justify-content-end">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
