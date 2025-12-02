<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decision Tree Analytics</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Inter', -apple-system, system-ui, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #2d3748;
        }
        
        nav { 
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 1.2rem 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        nav a { 
            color: #667eea;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        nav a:hover { color: #764ba2; }
        
        main { 
            padding: 2.5rem 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .card { 
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border: 1px solid rgba(255,255,255,0.8);
        }
        
        .card h1, .card h2 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }
        
        .error { 
            background: linear-gradient(135deg, #fc8181 0%, #f56565 100%);
            color: white;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(245,101,101,0.3);
        }
        
        input, button { 
            padding: 0.9rem;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            background: #f7fafc;
            color: #2d3748;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        
        input { width: 100%; margin-top: 0.5rem; }
        
        button { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 700;
            cursor: pointer;
            border: none;
            margin-top: 1rem;
            box-shadow: 0 4px 15px rgba(102,126,234,0.4);
        }
        
        button:hover { 
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102,126,234,0.5);
        }
        
        label { 
            display: block;
            margin-top: 1.2rem;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            margin: 0.2rem;
        }
        
        .badge-purple { background: #e9d5ff; color: #7c3aed; }
        .badge-green { background: #d1fae5; color: #047857; }
        .badge-amber { background: #fef3c7; color: #d97706; }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('home') }}">
            🌳 <span>Decision Tree Analytics</span>
        </a>
    </nav>
    <main>
        @yield('content')
    </main>
</body>
</html>