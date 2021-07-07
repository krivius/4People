<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="app.css" rel="stylesheet">

</head>
<body class="antialiased">
    <h1>Новости</h1>
    <ul>
    @foreach($data as $dataItem)
        <li>
            <a href="{{$dataItem->href}}">{{$dataItem->href}}</a>
        </li>
    @endforeach
    </ul>

</body>
</html>
