@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="col-lg-8 offset-lg-2">
                    <form method="post" action="/categories" class="mb-5" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                          <label for="category_name" class="form-label">Category Name</label>
                          <input type="text" class="form-control @error('category_name') is-invalid @enderror" id="category_name" name="category_name" autocomplete="on" required autofocus value="{{ old('category_name') }}">
                          @error('category_name')
                            <div class="invalid-feedback">
                              {{ $message }}
                            </div>
                          @enderror
                        </div> 
                        <button type="submit" class="btn btn-primary">Create Category</button>
                    </form>
                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
