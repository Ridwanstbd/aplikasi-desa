@extends('layouts.blog')

@section('content')
<div class="container my-4">
    <div class="mb-4">
        <h1 class="display-4">Tag: {{ $tag->name }}</h1>
        @if($tag->description)
            <p class="mt-2 text-muted">{{ $tag->description }}</p>
        @endif
    </div>

    <!-- Tag List -->
    <div class="mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('blog.index') }}" class="btn btn-light">Semua</a>
            @foreach(\App\Models\BlogTag::withCount('posts')->get() as $t)
                <a href="{{ route('blog.tag', $t->slug) }}" 
                   class="btn {{ $t->id === $tag->id ? 'btn-primary' : 'btn-light' }}">
                    {{ $t->name }}
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
                        <h2 class="card-title">
                            <a href="{{ route('blog.show', $post->slug) }}" 
                               class="text-dark text-decoration-none">
                                {{ $post->title }}
                            </a>
                        </h2>

                        <p class="card-text text-muted">
                            {{ $post->excerpt }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center">
                            <small>By {{ $post->author->name }}</small>
                            <small>
                                <time datetime="{{ $post->published_at ? \Illuminate\Support\Carbon::parse($post->published_at) : now()->format('Y-m-d\TH:i') }}">
                                    {{ $post->published_at ? \Illuminate\Support\Carbon::parse($post->published_at)->format('M d, Y') : now()->format('M d, Y') }}
                                </time>
                            </small>
                        </div>

                        @if($post->tags->isNotEmpty())
                            <div class="mt-2">
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
                <p class="text-muted">No posts found for this tag.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection