@extends('layout.frontend.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/assets/css/style.css">
    <style>
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
                                    <a href="#">
                                        <i class="fa fa-tag"></i>
                                        @foreach($artikel->kategoriartikels as $index => $kategori)
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
                    </div>
                    <div class="navigation-top">
                        <div class="d-sm-flex justify-content-between text-center">
                            <div class="col-sm-4 text-center my-2 my-sm-0">
                            </div>
                        </div>
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
                                        <a href="{{ route('fnews.detail', $article->id) }}">
                                            <h3>{{ $article->judul }}</h3>
                                        </a>
                                        <p>{{ $article->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </aside>
                    </div>
                </div>
            </div>

            <!-- Menampilkan Komentar dan Balasan -->
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            Komentar ({{ $comments->count() }})
        </div>
        <div class="card-body">
            @php
            // Fungsi rekursi untuk menampilkan komentar dan balasan
            function displayComments($comments, $level = 0) {
                if ($level >= 3) {
                    return;
                }
                foreach ($comments as $comment) {
                    echo '<div style="margin-left: ' . ($level * 25) . 'px;">';
                    echo '<strong>' . $comment->user->name . '</strong>';
                    echo '<p>' . $comment->content . '</p>';
                    echo '<small class="text-muted">' . $comment->created_at->diffForHumans() . '</small>';
                    echo '<a class="btn-reply"><i class="fas fa-reply"></i> </a>';
                    echo '<form action="' . route('comments.store') . '" method="POST" class="reply-form mt-2" style="display:none;">';
                    echo '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                    echo '<input type="hidden" name="artikel_id" value="' . $comment->artikel_id . '">';
                    echo '<input type="hidden" name="parent_id" value="' . $comment->id . '">';
                    echo '<div class="form-group">';
                    echo '<textarea class="form-control" name="content" rows="1" placeholder="Balas komentar ini"></textarea>';
                    echo '</div>';
                    echo '<button type="submit" class="btn-comment btn-sm btn-primary">Kirim</button>';
                    echo '</form>';

                    if ($comment->replies->count() > 0) {
                        displayComments($comment->replies, $level + 1);
                    }

                    echo '</div>';
                }
            }

            // Panggil fungsi rekursi dengan komentar utama yang tidak memiliki parent_id
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
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var replyButtons = document.querySelectorAll('.btn-reply');
            replyButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var replyForm = button.nextElementSibling;
                    if (replyForm.style.display === 'none') {
                        replyForm.style.display = 'block';
                    } else {
                        replyForm.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection

