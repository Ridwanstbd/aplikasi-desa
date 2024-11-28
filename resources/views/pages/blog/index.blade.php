@extends('layouts.blog')

@section('content')
<div class="container my-4">
    <div class="mb-4">
        <h1 class="display-4">Artikel Peternakan</h1>
    </div>

    <!-- Category List -->
    <div class="mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('blog.index') }}" 
               class="btn {{ request()->routeIs('blog.index') ? 'btn-primary' : 'btn-light' }}">
                Semua
            </a>
            @foreach(\App\Models\BlogCategory::withCount('posts')->get() as $category)
                <a href="{{ route('blog.category', $category->slug) }}" 
                   class="btn {{ request()->is('blog/category/'.$category->slug) ? 'btn-primary' : 'btn-light' }}">
                    {{ $category->name }} ({{ $category->posts_count }})
                </a>
            @endforeach
        </div>
    </div>

    <!-- Posts Grid -->
    <div class="row">
        @forelse($posts as $post)
            <div class="col-md-6 col-lg-4 mb-4">
                <article class="card shadow-sm">
                    @if($post->featured_image)
                        <img src="{{ Storage::url($post->featured_image) }}" 
                             alt="{{ $post->title }}" 
                             class="card-img-top">
                    @endif
                    
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('blog.category', $post->category->slug) }}" 
                               class="badge bg-info text-dark">
                                {{ $post->category->name }}
                            </a>
                        </div>

                        <h2 class="card-title">
                            <a href="{{ route('blog.show', $post->slug) }}" 
                               class="text-dark text-decoration-none">
                                {{ $post->title }}
                            </a>
                        </h2>

                        <p class="card-text text-truncate">
                            {{ $post->excerpt }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center">
                            <small>By {{ $post->author->name }}</small>
                            <small>
                                <time datetime="{{ $post->published_at ? \Illuminate\Support\Carbon::parse($post->published_at) : now()->format('Y-m-d\TH:i')}}">
                                    {{ $post->published_at ? \Illuminate\Support\Carbon::parse($post->published_at)->format('M d, Y') : now()->format('M d, Y')}}
                                </time>
                            </small>
                        </div>

                        @if($post->tags->isNotEmpty())
                            <div class="mt-3">
                                @foreach($post->tags as $tag)
                                    <a href="{{ route('blog.tag', $tag->slug) }}" 
                                       class="badge bg-secondary text-light">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">No posts found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection