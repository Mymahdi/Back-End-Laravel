<!DOCTYPE html>
<html>
<head>
    <title>Blog Published</title>
</head>
<body>
    <h1>{{ $authorName }} just published a new blog!</h1>
    <p>Blog Title: {{ $blogTitle }}</p>
    <p>Author Email: {{ $authorEmail }}</p>
    {{-- <p>You can read it here: <a href="{{ $blogLink }}">{{ $blogTitle }}</a></p> --}}
    <p>Thank you for staying updated with our content!</p>
</body>
</html>
