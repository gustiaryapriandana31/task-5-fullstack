@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>

                <div class="table-responsive col-lg-10 offset-lg-1">
                    <a href="/posts/create" class="btn btn-primary mb-3">Create new post</a>
                
                    <div class="container">
                        <div class="row my-3">
                            <div class="col-lg-8">
                                <h1 class="mb-3">{{ $post->title }}</h1>
                    
                                <a href="/posts" class="btn btn-success"><span data-feather="arrow-left" ></span> Back to list posts</a>
                    
                                <a href="/posts/edit/{{ $post->id }}" class="btn btn-warning"><span data-feather="edit" ></span> Edit</a>
                                
                                <form action="/posts/delete/{{ $post->id }}" method="post" class="d-inline">
                                    @method('delete')
                                    @csrf
                                    <button class="btn btn-danger" onclick="return confirm('Are your sure?')"><span data-feather="x-circle" ></span> Delete</button>
                                </form>
                    
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
    </div>
</div>
@endsection
