@extends('layout.frontend.app')
@section('css')
  <style>
            /* CSS */
        .single_sidebar_widget .media.post_item {
            display: flex;
            align-items: center;
            margin-bottom: 15px; /* Atur margin bawah untuk jarak antar item */
        }

        .single_sidebar_widget .media.post_item img {
            flex: 0 0 100px; /* Lebar foto tetap 100px, tidak fleksibel */
            height: 70px; /* Tinggi foto */
            object-fit: cover; /* Untuk mengisi gambar ke dalam ukuran yang ditentukan */
        }

        .single_sidebar_widget .media.post_item .media-body {
            flex: 1; /* Bagian body mengisi sisa ruang */
            margin-left: 10px; /* Jarak antara foto dan teks */
        }

  </style>
  <link rel="stylesheet" href="{{asset('assets')}}/assets/css/style.css">
@endsection

@section('content')
    <section class="blog_area pt-5 pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="blog_left_sidebar">
                        @foreach ($artikels as $d)

                        <article class="blog_item">
                            <div class="blog_item_img">
                                <img class="card-img rounded-0" src="{{asset('')}}{{ $d->photo }}" alt="">
                                <a href="#" class="blog_item_date">
                                    <h3>{{ $d->created_at->format('d') }}</h3>
                                    <p>{{ $d->created_at->format('M') }}</p>
                                </a>
                            </div>

                            <div class="blog_details">
                                <a class="d-inline-block" href="{{ route('fnews.detail', $d->judul) }}">
                                    <h2>{{$d->judul}}</h2>
                                </a>
                                <p>  {!! \Illuminate\Support\Str::words($d->konten, 30, '...') !!}
                                    @if (str_word_count($d->konten) > 30)
                                        <a href="{{ route('fnews.detail', $d->judul) }}">Lihat Selengkapnya</a>
                                    @endif</p>
                                    <ul class="blog-info-link">
                                    <li><a href="#"><i class="fa fa-user"></i>{{$d->user->name}}</a></li>
                                    @foreach($d->kategoriartikels as $index => $kategori)
                                    <li><a href="{{ route('fnews', $kategori->deskripsi) }}"><i class="fa fa-tag"></i>
                                            {{ $kategori->nama_kategori }}
                                            @if($index < count($d->kategoriartikels) - 1)
                                                ,
                                            @endif
                                        @endforeach
                                    </a></li>
                                </ul>
                            </div>
                        </article>
                        @endforeach

                        {{-- <nav class="blog-pagination justify-content-center d-flex">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a href="#" class="page-link" aria-label="Previous">
                                        <i class="ti-angle-left"></i>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a href="#" class="page-link">1</a>
                                </li>
                                <li class="page-item active">
                                    <a href="#" class="page-link">2</a>
                                </li>
                                <li class="page-item">
                                    <a href="#" class="page-link" aria-label="Next">
                                        <i class="ti-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav> --}}
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
                            <a href="{{ route('fnews.detail', $article->judul) }}">
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
        </div>
    </section>
@endsection
@section('scripts')
  <script src="{{asset('assets')}}/assets/js/vendor/modernizr-3.5.0.min.js"></script>
		<!-- Jquery, Popper, Bootstrap -->
		<script src="{{asset('assets')}}/assets/js/vendor/jquery-1.12.4.min.js"></script>
        <script src="{{asset('assets')}}/assets/js/popper.min.js"></script>
        <script src="{{asset('assets')}}/assets/js/bootstrap.min.js"></script>

@endsection
