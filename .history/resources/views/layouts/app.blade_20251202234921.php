<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decision Tree</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, sans-serif; background: #0f0f0f; color: #f5f5f5; }
        nav { background: #1a1a1a; border-bottom: 1px solid #333; padding: 1rem 2rem; margin-bottom: 2rem; }
        nav a { color: #f5f5f5; text-decoration: none; font-weight: 600; font-size: 1.2rem; }
        main { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .card { background: #1a1a1a; border: 1px solid #333; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; }
        .error { background: #3d1414; border: 1px solid #5e1f1f; padding: 1rem; margin-bottom: 1rem; border-radius: 4px; color: #ff6b6b; }
        input, button { padding: 0.75rem; border-radius: 4px; border: 1px solid #333; background: #1a1a1a; color: #f5f5f5; }
        input { width: 100%; margin-top: 0.5rem; }
        button { background: #d4a017; color: #000; font-weight: 600; cursor: pointer; border: none; margin-top: 1rem; }
        button:hover { background: #edb82f; }
        label { display: block; margin-top: 1rem; font-weight: 600; color: #aaa; }
    </style>
</head>
<body>
    <nav><a href="{{ route('home') }}">🌳 Decision Tree</a></nav>
    <main>@yield('content')</main>
</body>
</html>