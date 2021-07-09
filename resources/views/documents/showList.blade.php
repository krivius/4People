@extends('layout')

@section('content')


    <div id="wrapper">
        <h1>Новости</h1>
        @foreach($news as $article)
            <div class="shortStory">
                <h2>{{$article->title}}</h2>
                {{ substr($article->article, 0, 200) }}

                <a href="/news/{{$article->id}}" class="showFull">Подробнее</a>
            </div>

        @endforeach
    </div>
@endsection
