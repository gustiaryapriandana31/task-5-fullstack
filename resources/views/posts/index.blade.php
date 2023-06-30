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
                
                    <table class="table table-striped table-lg">
                      <thead>
                        <tr>
                          <th scope="col">No</th>
                          <th scope="col">Title</th>
                          <th scope="col">Content</th>
                          <th scope="col">Author</th>
                          <th scope="col">Category</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($posts as $post)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $post->title }}</td>
                                <td>{{ $post->content }}</td>
                                <td>{{ $post->user->name }}</td>
                                <td>{{ $post->category->name }}</td>
                                <td>
                                  <a href="/posts/detail/{{ $post->id }}" class="badge bg-primary border-0 text-decoration-none"><span data-feather="eye" ></span>Detail</a>
                
                                  <a href="/posts/edit/{{ $post->id }}" class="badge bg-warning border-0 text-decoration-none"><span data-feather="edit" ></span>Update</a>
                
                                  <form action="/posts/delete/{{ $post->id }}" method="post" class="d-inline">
                                    @method('delete')
                                    @csrf
                                    <button class="badge bg-danger border-0" onclick="return confirm('Are your sure?')"><span data-feather="x-circle" ></span>Delete</button>
                                  </form>
                                </td>
                            </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
