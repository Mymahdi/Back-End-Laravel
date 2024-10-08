<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $blog->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #e74c3c;
            padding-bottom: 10px;
        }
        p {
            line-height: 1.6;
            margin: 15px 0;
        }
        .author {
            margin-top: 20px;
            font-style: italic;
            color: #555;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #3498db;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $blog->title }}</h1>
        <p>{{ $blog->body }}</p>
        <p class="author"><strong>Author:</strong> {{ $blog->author->first_name.' '.$blog->author->last_name }}</p>
        <div class="tags">
            <strong>Tags:</strong>
            @foreach($blog->tags as $tag)
                <span>{{ $tag->name }}{{ !$loop->last ? ',' : '' }}</span>
            @endforeach
        </div>
    </div>
</body>
</html>
