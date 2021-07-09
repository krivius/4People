@extends('layout')

@section('content')
    <div id="wrapper">
        <h1>{{$article->title}}</h1>

        @if($article->img)
            <img src="{{$article->img}}">
        @endif
        <div class="fullStory">
            {{$article->article}}
        </div>

        <a href="{{$article->link}}" class="origLink">Оригинал новости</a>

    </div>

@endsection
