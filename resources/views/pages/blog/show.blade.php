@extends('layouts.blog')

@section('meta_title', $post->title)
@section('meta_description', Str::limit(strip_tags($post->excerpt), 160))

@push('styles')
<style>
    /* Trix Editor Content Styling */
    .trix-content {
        width: 100%;
        color: #212529;
    }

    .trix-content h1 {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .trix-content h2 {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .trix-content h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .trix-content p {
        margin-bottom: 1rem;
        line-height: 1.6;
    }

    .trix-content ul, .trix-content ol {
        margin-bottom: 1rem;
        padding-left: 2rem;
    }

    .trix-content li {
        margin-bottom: 0.5rem;
    }

    .trix-content figure {
        margin: 1.5rem 0;
    }

    .trix-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.375rem;
    }

    .trix-content figcaption {
        text-align: center;
        color: #6c757d;
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }

    .trix-content blockquote {
        border-left: 4px solid #dee2e6;
        padding-left: 1rem;
        margin-left: 0;
        margin-bottom: 1rem;
        color: #6c757d;
    }

    .trix-content pre {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.375rem;
        overflow-x: auto;
        margin-bottom: 1rem;
    }

    .trix-content a {
        color: #0d6efd;
        text-decoration: none;
    }

    .trix-content a:hover {
        text-decoration: underline;
    }

    /* Component Styles */
    .tag-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .tag-link {
        display: inline-block;
        background-color: #f8f9fa;
        color: #495057;
        border-radius: 50rem;
        padding: 0.25rem 1rem;
        font-size: 0.875rem;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .tag-link:hover {
        background-color: #e9ecef;
        color: #212529;
        text-decoration: none;
    }

    .share-list {
        display: flex;
        gap: 1rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .share-btn {
        text-decoration: none;
        color: #6c757d;
        font-size: 1.25rem;
        transition: color 0.2s;
    }

    .share-btn:hover.twitter { color: #1DA1F2; }
    .share-btn:hover.facebook { color: #4267B2; }
    .share-btn:hover.whatsapp { color: #25D366; }
    .share-btn:hover.copy-link { color: #212529; }

    .notification {
        position: fixed;
        bottom: 1rem;
        right: 1rem;
        background-color: #212529;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        z-index: 1050;
    }

    .article-metadata {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }

    .article-metadata-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>
@endpush

@section('content')
<main class="container py-5">
    <article class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <header class="mb-4">
                <h1 class="display-4 fw-bold mb-3">{{ $post->title }}</h1>

                <div class="article-metadata">
                    <address class="article-metadata-item">
                        <i class="bi bi-person-circle" aria-hidden="true"></i>
                        <span rel="author">{{ $post->author->name }}</span>
                    </address>
                    <div class="article-metadata-item">
                        <i class="bi bi-calendar3" aria-hidden="true"></i>
                        <time datetime="{{ $post->published_at->toIso8601String() }}">
                            {{ $post->published_at->format('d M Y') }}
                        </time>
                    </div>
                    <div class="article-metadata-item">
                        <i class="bi bi-folder" aria-hidden="true"></i>
                        <a href="{{ route('blog.category', $post->category->slug) }}"
                           class="text-decoration-none text-muted"
                           rel="category">
                            {{ $post->category->name }}
                        </a>
                    </div>
                </div>

                @if($post->excerpt)
                    <p class="lead text-secondary mb-4">
                        {{ $post->excerpt }}
                    </p>
                @endif
            </header>

            <section class="trix-content mb-4">
                {!! $post->content !!}
            </section>

            @if($post->tags->count() > 0)
                <section class="border-top pt-4 mb-4" aria-label="Article tags">
                    <h2 class="h5 fw-bold mb-3">Tags:</h2>
                    <ul class="tag-list">
                        @foreach($post->tags as $tag)
                            <li>
                                <a href="{{ route('blog.tag', $tag->slug) }}"
                                   class="tag-link"
                                   rel="tag">
                                    #{{ $tag->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <section class="border-top pt-4" aria-label="Share article">
                <h2 class="h5 fw-bold mb-3">Share this article:</h2>
                <ul class="share-list">
                    <li>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="share-btn twitter"
                           aria-label="Share on Twitter">
                            <i class="bi bi-twitter" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="share-btn facebook"
                           aria-label="Share on Facebook">
                            <i class="bi bi-facebook" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="share-btn whatsapp"
                           aria-label="Share on WhatsApp">
                            <i class="bi bi-whatsapp" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <button type="button"
                                class="share-btn copy-link border-0 bg-transparent p-0"
                                onclick="copyToClipboard()"
                                aria-label="Copy link to clipboard">
                            <i class="bi bi-link-45deg" aria-hidden="true"></i>
                        </button>
                    </li>
                </ul>
            </section>
        </div>
    </article>
</main>
@endsection

@push('scripts')
<script>
    function copyToClipboard() {
        navigator.clipboard.writeText('{{ url()->current() }}');

        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.setAttribute('role', 'alert');
        notification.textContent = 'Link copied to clipboard!';
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 2000);
    }
</script>
@endpush