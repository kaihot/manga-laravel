@extends('layout')


@section("ogp")
    <title>{{implode(",", $query)}}|漫画1コマネタ画像検索</title>
    <meta name="keywords" content="漫画,1コマ,twitter,リプ画像,煽り画像,ネタ画像">
    <meta name="description" content="『{{implode(",", $query)}}』の漫画1コマネタ画像一覧">
@endsection

@section('contents')

    <div class="container">
        <div class="row my-5">
            <div class="col mx-auto">
                {{ Form::open(['method' => 'GET', 'url' => 'search']) }}
                <div class="input-group mb-3">
                    {{ Form::input('検索する', 'query', null, ["class" => "form-control mr-1", "placeholder"=> "キーワード"]) }}
                    {{ Form::submit("検索",["class"=>"btn btn-outline-primary"])}}
                </div>
                {{ Form::close() }}
            </div>
        </div>
        <div class="alert alert-primary" role="alert">
            Tips : 思うような検索結果出ない場合は「ひらがな」や検索ワードを短くすると出る場合があります。
        </div>
    </div>
    <div class="container">
        <div class="row">
            <h5>- 人気タグ -</h5>
        </div>
        <div class="mb-4">
            @foreach($popular_tags as $tag)
                <a href="/search?query={{$tag}}" style="text-decoration: none;">
                    <span class="badge badge-pill badge-secondary mb-1">{{$tag}}</span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="container">
        <div class="row">
            <h5>- 新着画像 -</h5>
        </div>
        <!-- Previous Page Link -->
        {{$imagePaginate->appends($params)->links()}}
        <div class="row">
            @foreach($images as $key => $image)
                @if(\Agent::isMobile() && ($key == 1 || $key == 6))

                    <div class="col-12 col-md-4 col-lg-3 col-sm-6 mb-2">
                    {{--<div class="card">--}}
                    {{--<div class="card-body">--}}
                    {{--<div class="mx-auto mt-3">--}}
                    {{--<script src="//adm.shinobi.jp/s/2a9af09f152b6774dbe09aaeff953a94"></script>--}}
                    <!-- manga-top -->
                        <ins class="adsbygoogle"
                             style="display:block"
                             data-ad-client="ca-pub-1691009953433743"
                             data-ad-slot="1248830620"
                             data-ad-format="auto"></ins>
                        <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                        </script>
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    </div>
                @endif
                <div class="col col-md-4 col-lg-3 col-sm-6 mb-2">
                    <div class="card mx-auto">
                        <a href="/images/{{$image["_id"]}}">
                            <div style="">
                                <img class="card-img-top" style="object-fit: contain;width: 100%;" src='{{$image["thumbnail"]}}' alt="Card image cap">

                            </div>
                        </a>
                        <div class="card-body">
                            @foreach($image["_source"]["tags"] as $tag)
                                <a href="/search?query={{$tag}}" style="text-decoration: none;">
                                    <span style=" white-space: normal !important;" class="badge badge-pill badge-secondary mb-1 text-justify">{{$tag}}</span>
                                </a>
                            @endforeach
                            <a href="/images/{{$image["_id"]}}" class="btn btn-outline-success btn-lg btn-block">SNSで使う</a>
                        </div>
                    </div>
                </div>
            @endforeach
                <div class="col-12 col-md-4 col-lg-3 col-sm-6 mb-2">
                {{--<div class="card">--}}
                {{--<div class="card-body">--}}
                {{--<div class="mx-auto mt-3">--}}
                {{--<script src="//adm.shinobi.jp/s/2a9af09f152b6774dbe09aaeff953a94"></script>--}}
                <!-- manga-top -->
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-1691009953433743"
                         data-ad-slot="1248830620"
                         data-ad-format="auto"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                </div>
        </div>
        {{$imagePaginate->appends($params)->links()}}

    </div>
@endsection