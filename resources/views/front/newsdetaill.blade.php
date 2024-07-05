@extends('layout.frontend.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/css/style.css">
    <style>
        .btn-reply {
            color: #007bff;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-reply:hover {
            text-decoration: underline;
        }

        .btn-comment {
            top: 10px;
            right: 10px;
            z-index: 1000;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            padding: 8px 12px;
            font-size: 16px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .btn-comment:hover {
            background-color: #0056b3;
        }
        .media {
    display: flex;
    align-items: center;
    margin-bottom: 15px; /* Optional: untuk memberikan jarak antar item */
}

.media-left {
    margin-right: 10px; /* Optional: untuk memberikan jarak antara gambar dan teks */
}

.media img {
    max-width: 100px; /* Atur lebar maksimum gambar */
    height: auto; /* Biarkan tinggi gambar menyesuaikan */
}

.media-body {
    flex: 1; /* Agar teks bisa mengisi sisa ruang */
}

.media-body h3 {
    margin-bottom: 5px; /* Optional: untuk memberikan jarak antara judul dan tanggal */
}

    </style>

@endsection

@section('content')
    <section class="blog_area single-post-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 posts-list">
                    <div class="single-post">
                        <div class="feature-img">
                            <img class="img-fluid" src="{{ asset('') }}{{ $artikel->photo }}" alt="">
                        </div>
                        <div class="blog_details">
                            <h2>{{ $artikel->judul }}</h2>
                            <ul class="blog-info-link mt-3 mb-4">
                                <li>
                                        <i class="fa fa-tag"></i>
                                        @foreach($artikel->kategoriartikels as $index => $kategori)
                                        <a href="{{ route('fnews', $kategori->deskripsi) }}">
                                            {{ $kategori->nama_kategori }}
                                            @if($index < count($artikel->kategoriartikels) - 1)
                                                ,
                                            @endif
                                        @endforeach
                                    </a>
                                </li>
                                <li><a href="#"><i class="fa fa-user"></i>{{ $artikel->user->name }}</a></li>
                                <li><a href="#"><i class="fa fa-comments"></i>{{ $comments->count() }} Komentar</a></li>
                                <li><a href="#"><i class="fa fa-clock"></i>{{ $artikel->created_at->diffForHumans() }}</a></li>
                                <li><a href="#"><i class="fa fa-calendar"></i>{{ $artikel->created_at }}</a></li>
                                <li><i class="fa fa-share"></i>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::url()) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="fab fa-facebook"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(Request::url()) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                            <p class="excert">
                                {!! $artikel->konten !!}
                            </p>
                        </div>

                        <section class="gradient-custom">
                            <div class="card">
                                <div class="card-body p-4">
                                    <h4 class="text-center mb-4 pb-2">Komentar ({{ $comments->count() }})</h4>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @php
                                            function displayComments($comments, $level = 0) {
                                                if ($level >= 2) {
                                                    return;
                                                }
                                                foreach ($comments as $comment) {
                                                    $userPhoto = $comment->user->photo ? asset('') . $comment->user->photo : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($comment->user->email))) . '?d=identicon&s=65';
                                                    echo '<div class="d-flex flex-start mb-4">';
                                                    echo '<img class="rounded-circle shadow-1-strong me-3" src="' . $userPhoto . '" alt="avatar" width="65" height="65"/>';
                                                    echo '<div class="flex-grow-1 flex-shrink-1">';
                                                    echo '<div class="d-flex justify-content-between align-items-center">';
                                                    echo '<p class="mb-1">' . $comment->user->name . ' <span class="small">- ' . $comment->created_at->diffForHumans() . '</span></p>';
                                                    echo '</div>';
                                                    echo '<p class="small mb-0">' . $comment->content . '</p>';
                                                    if ($level < 1) {
                                                        echo '<a class="btn-reply"><i class="fas fa-reply fa-xs"></i><span class="small"> reply</span> </a>';
                                                        echo '<form action="' . route('comments.store') . '" method="POST" class="reply-form mt-2" style="display:none;">';
                                                        echo '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                                                        echo '<input type="hidden" name="artikel_id" value="' . $comment->artikel_id . '">';
                                                        echo '<input type="hidden" name="parent_id" value="' . $comment->id . '">';
                                                        echo '<div class="form-group">';
                                                        echo '<textarea class="form-control" name="content" rows="1" placeholder="Balas komentar ini"></textarea>';
                                                        echo '</div>';
                                                        echo '</form>';
                                                    }
                                                    if ($comment->replies->count() > 0) {
                                                        displayComments($comment->replies, $level + 1);
                                                    }
                                                    echo '</div>';
                                                    echo '</div>';
                                                }
                                            }
                                            displayComments($comments->where('parent_id', 0)->sortByDesc('created_at')->values());
                                            @endphp

                                            <div class="card mt-5">
                                                <div class="card-header">
                                                    Berikan Komentar
                                                </div>
                                                <div class="card-body">
                                                    <form action="{{ route('comments.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="artikel_id" value="{{ $artikel->id }}">
                                                        <div class="form-group">
                                                            <textarea class="form-control" name="content" rows="3" placeholder="Tulis komentar Anda"></textarea>
                                                        </div>
                                                        <button type="submit" class="btn-comment btn-primary">Kirim Komentar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Section komentar berakhir di sini -->
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="blog_right_sidebar">
                        <aside class="single_sidebar_widget search_widget">
                            <form action="{{ route('news.search') }}" method="GET">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input type="text" name="keyword" class="form-control" placeholder='Masukkan Kata Kunci'
                                            onfocus="this.placeholder = ''"
                                            onblur="this.placeholder = 'Search Keyword'">
                                        <div class="input-group-append">
                                            <button class="btns" type="button"><i class="ti-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <button class="button rounded-0 primary-bg text-white w-100 btn_1 boxed-btn"
                                    type="submit">Search</button>
                            </form>
                        </aside>

                        <aside class="single_sidebar_widget post_category_widget">
                            <h4 class="widget_title">Kategori</h4>
                            <ul class="list cat-list">
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('fnews', $category->deskripsi) }}" class="d-flex">
                                            <p>{{ $category->nama_kategori }}</p>
                                            <p>({{ $category->artikels_count }})</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </aside>

<aside class="single_sidebar_widget popular_post_widget">
    <h3 class="widget_title">Artikel Terbaru</h3>
    @foreach($artikel_terbaru as $article)
    <div class="media post_item">
        <img src="{{ asset($article->photo) }}" alt="post" width="100" height="70">
        <div class="media-body ml-2">
            <div class="media-content">
                <a href="{{ route('fnews.detail', $article->id) }}">
                    <h3>{{ $article->judul }}</h3>
                </a>
                <p>{{ $article->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>
    @endforeach
</aside>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var replyButtons = document.querySelectorAll('.btn-reply');
            replyButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var commentElement = button.closest('.d-flex.flex-start');
                    var replyForm = commentElement.querySelector('.reply-form');
                    if (replyForm.style.display === 'none' || replyForm.style.display === '') {
                        replyForm.style.display = 'block';
                    } else {
                        replyForm.style.display = 'none';
                    }
                });
            });

            var replyForms = document.querySelectorAll('.reply-form');
            replyForms.forEach(function (form) {
                var textarea = form.querySelector('textarea');
                textarea.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();  // Prevent default behavior (new line)
                        form.submit();  // Submit the form
                    }
                });
            });
        });
    </script>
@endsection
