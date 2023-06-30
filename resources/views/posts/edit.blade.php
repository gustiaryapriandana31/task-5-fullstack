@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="col-lg-8 offset-lg-2">
                    <form method="post" action="{{ route('update-post', [$post->id]) }}" class="mb-5" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" autocomplete="on" required autofocus value="{{ old('title', $post->title) }}">
                            @error('title')
                                <div class="invalid-feedback">
                                {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" name="category_id">
                                @foreach ($categories as $category)
                                    @if (old('category_id', $post->category_id) == $category->id)
                                        <option value="{{ $category->id }}" selected>{{ $category->name }}</option>     
                                    @else
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endif     
                                @endforeach
                            </select>
                        </div>
                
                        <div class="mb-3">
                            <label for="image" class="form-label">Post Image</label>
                            <img class="img-preview img-fluid mb-3 col-sm-5">
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" onchange="previewImage()">
                            @error('image')
                                <div class="invalid-feedback">
                                {{ $message }}
                                </div>
                            @enderror 
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <input type="text" class="form-control @error('content') is-invalid @enderror" id="content" name="content" required value="{{ old('content', $post->content) }}">
                            @error('content')
                                <div class="invalid-feedback">
                                {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
   // menampilkan preview gambar
   function previewImage() {
      const image = document.querySelector('#image');
      const imgPreview = document.querySelector('.img-preview');
      
      imgPreview.style.display = 'block';
      
      const oFReader = new FileReader();
      oFReader.readAsDataURL(image.files[0]);
      
      oFReader.onload =  function(oFREevent) {
        imgPreview.src = oFREevent.target.result;
      }
    }
</script>
@endsection
