<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blog->title }}</title>
</head>
<body>
    <h1>{{ $blog->title }}</h1>
    <p>{{ $blog->body }}</p>
    {{-- <p><strong>Author:</strong> {{ $blog->author->name }}</p>  --}}
    {{-- <a href="{{ url('/') }}">Back to Blog List</a> --}}
</body>
</html>
