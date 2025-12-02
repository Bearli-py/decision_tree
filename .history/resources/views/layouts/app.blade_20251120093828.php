<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Decision Tree Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #0070C0;
            --success-color: #00B050;
            --danger-color: #FF0000;
            --light-bg: #F5F5F5;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
        }

        .nav-link {
            margin-right: 1rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #FFD700 !important;
        }

        .nav-link.active {
            border-bottom: 3px solid #FFD700;
            padding-bottom: 0.5rem;
        }

        .page-header {
            background: white;
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-left: 5px solid var(--primary-color);
        }

        .page-header h1 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
            color: white;
            border: none;
            font-weight: bold;
            border-radius: 8px 8px 0 0 !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,112,192,0.3);
        }

        .btn-success {
            background-color: var(--success-color);
            border: none;
        }

        .btn-success:hover {
            background-color: #008C3A;
        }

        .badge {
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .badge-success {
            background-color: var(--success-color);
        }

        .badge-danger {
            background-color: var(--danger-color);
        }

        .badge-info {
            background-color: var(--primary-color);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            font-weight: bold;
        }

        .table tbody tr:hover {
            background-color: #F0F8FF;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .stat-card .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .tree-node {
            background: white;
            padding: 1rem;
            border-radius: 6px;
            margin: 0.5rem;
            border-left: 4px solid var(--primary-color);
            font-family: 'Courier New', monospace;
        }

        .tree-node.leaf {
            border-left-color: var(--success-color);
        }

        .tree-node.leaf.success {
            background-color: #E8F5E9;
        }

        .tree-node.leaf.fail {
            background-color: #FFEBEE;
            border-left-color: var(--danger-color);
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid #ddd;
            padding: 0.7rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0,112,192,0.25);
        }

        .result-box {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-top: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .result-box.success {
            border-left: 5px solid var(--success-color);
        }

        .result-box.fail {
            border-left: 5px solid var(--danger-color);
        }

        .result-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .path-list {
            background: #F5F5F5;
            padding: 1rem;
            border-radius: 6px;
            margin: 1rem 0;
            text-align: left;
        }

        .path-item {
            padding: 0.5rem 0;
            font-family: 'Courier New', monospace;
            border-bottom: 1px solid #ddd;
        }

        .path-item:last-child {
            border-bottom: none;
        }

        footer {
            background: var(--primary-color);
            color: white;
            padding: 2rem;
            margin-top: 3rem;
            text-align: center;
        }

        .comparison-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .comparison-table th {
            background-color: var(--primary-color);
            color: white;
        }

        .comparison-table td {
            padding: 1rem;
        }

        .comparison-table tr:hover {
            background-color: #F0F8FF;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-lg">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-diagram-3"></i> Decision Tree Event
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dataset') ? 'active' : '' }}" href="{{ route('dataset') }}">
                            <i class="bi bi-table"></i> Dataset
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tree') ? 'active' : '' }}" href="{{ route('tree') }}">
                            <i class="bi bi-diagram-2"></i> Tree
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('predict') ? 'active' : '' }}" href="{{ route('predict') }}">
                            <i class="bi bi-magic"></i> Predict
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('comparison') ? 'active' : '' }}" href="{{ route('comparison') }}">
                            <i class="bi bi-check2-all"></i> Comparison
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container-lg">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p class="mb-0">&copy; 2024 Decision Tree Event Prediction | Built with Laravel</p>
        <small>Mahasiswa: Maria | Politeknik Negeri Jember</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @stack('scripts')
</body>
</html>