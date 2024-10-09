<!DOCTYPE html>
<html>
<head>
    <title>Blog Published</title>
</head>
<body>


    <h1>{{ $authorFirstName . ' ' . $authorLastName }} just published a new blog!</h1>
    <p>Blog Title: {{ $blogTitle }}</p>
    <p>Author Email: {{ $authorEmail }}</p>
    <p>You can read it here: <a href= {{$blogLink}} >{{ $blogTitle }}</a></p>
    <p>Best Regards!</p>
</body>
</html>
