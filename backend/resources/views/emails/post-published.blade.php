<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .post-title {
            color: #1f2937;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .post-excerpt {
            color: #6b7280;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            background-color: #4F46E5;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
        .unsubscribe {
            color: #9ca3af;
            text-decoration: none;
            margin-top: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📝 New Blog Post Published!</h1>
    </div>

    <div class="content">
        <h2 class="post-title">{{ $post->title }}</h2>
        
        <p class="post-excerpt">
            {{ $post->excerpt }}
        </p>

        <p>
            <strong>Author:</strong> {{ $post->user->name }}<br>
            <strong>Published:</strong> {{ $post->published_at->format('F j, Y') }}
        </p>

        <a href="{{ $postUrl }}" class="button">
            Read Full Article →
        </a>
    </div>

    <div class="footer">
        <p>You're receiving this email because you subscribed to our blog newsletter.</p>
        <a href="{{ $unsubscribeUrl }}" class="unsubscribe">
            Unsubscribe from newsletter
        </a>
    </div>
</body>
</html>
