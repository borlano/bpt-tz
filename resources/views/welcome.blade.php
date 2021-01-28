<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BPT</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="antialiased">
<div class="container">
    <h1>Тестовое задание</h1>
    <form class="m-5" action="{{ route('image') }}" method="post" enctype="multipart/form-data">
        @csrf
        <label for="image">Выберите изображение</label>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <input class="form-control-file" type="file" name="image">
        <input class="form-control btn-block" type="submit">
    </form>
</div>
</body>
</html>
