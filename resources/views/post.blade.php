@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="container">
                <div class="row my-3">
                    <div class="col-lg-8">
                        <h1 class="mb-3">{{ $post->title }}</h1>
            
                        <a href="/home" class="btn btn-primary"><span data-feather="arrow-left" ></span> Back to list posts</a>
            
                        @if ($post->image)
                            <div style="max-height: 350px; overflow:hidden;">
                                <img src="{{ asset($post->image) }}" class="card-img-top img-fluid mt-3" alt="{{ $post->category->name }}">
                            </div>
                        @else
                            <img src="https://source.unsplash.com/1200x400?{{ $post->category->name }}" class="card-img-top img-fluid mt-3" alt="{{ $post->category->name }}">
                        @endif
            
                        <article class="my-3 fs-5">
                            {!! $post->content !!}
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
