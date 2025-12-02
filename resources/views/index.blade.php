<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decision Tree ID3 - Event Workshop Predictor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* NAVBAR */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea !important;
            letter-spacing: -0.5px;
        }

        .navbar-brand i {
            margin-right: 8px;
        }

        /* MAIN CONTAINER */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .hero-content {
            max-width: 900px;
            width: 100%;
        }

        .hero-text {
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }

        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            letter-spacing: -1px;
        }

        .hero-text p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.95;
            line-height: 1.6;
        }

        /* UPLOAD CARD */
        .upload-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 50px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .upload-card h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 2rem;
        }

        .upload-card .subtitle {
            color: #888;
            margin-bottom: 40px;
            font-size: 1.1rem;
        }

        /* UPLOAD AREA */
        .upload-area {
            border: 3px dashed #667eea;
            border-radius: 15px;
            padding: 60px 40px;
            text-align: center;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .upload-area:hover {
            border-color: #764ba2;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            transform: translateY(-5px);
        }

        .upload-area i {
            font-size: 3.5rem;
            color: #667eea;
            margin-bottom: 20px;
            display: block;
        }

        .upload-area h3 {
            color: #333;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .upload-area p {
            color: #888;
            margin: 0;
        }

        #fileInput {
            display: none;
        }

        /* BUTTON */
        .btn-upload {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 15px 50px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .btn-upload:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-upload:active {
            transform: translateY(-1px);
        }

        /* INFO CARDS */
        .info-section {
            margin-top: 60px;
        }

        .info-section h3 {
            color: white;
            font-size: 1.5rem;
            margin-bottom: 30px;
            font-weight: 700;
            text-align: center;
        }

        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .info-card i {
            font-size: 2.5rem;
            color: #667eea;
            margin-bottom: 15px;
            display: block;
        }

        .info-card h4 {
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .info-card p {
            color: #888;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* FILE INFO */
        .file-info {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
        }

        .file-info.success {
            background: rgba(76, 175, 80, 0.2);
            border-color: rgba(76, 175, 80, 0.5);
        }

        .file-info.error {
            background: rgba(244, 67, 54, 0.2);
            border-color: rgba(244, 67, 54, 0.5);
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 30px;
            color: white;
            margin-top: 60px;
            background: rgba(0, 0, 0, 0.1);
        }

        footer p {
            margin: 0;
            font-size: 0.95rem;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .hero-text h1 {
                font-size: 2.2rem;
            }

            .hero-text p {
                font-size: 1rem;
            }

            .upload-card {
                padding: 30px;
            }

            .upload-area {
                padding: 40px 20px;
            }

            .upload-area i {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-tree"></i> Decision Tree ID3
            </a>
            <span class="navbar-text ms-auto text-muted">
                Event Workshop Predictor
            </span>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <div class="hero-section">
        <div class="hero-content">
            <!-- HERO TEXT -->
            <div class="hero-text">
                <h1>🌳 Decision Tree</h1>
                <p>Prediksi kesuksesan event workshop menggunakan algoritma ID3</p>
            </div>

            <!-- UPLOAD CARD -->
            <div class="upload-card">
                <h2>Upload Dataset CSV</h2>
                <p class="subtitle">Pilih file CSV dengan data event workshop Anda</p>

                <form id="uploadForm" method="POST" action="/upload" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <h3>Klik atau drag file CSV ke sini</h3>
                        <p>Pastikan format: No, Peserta, Budget, Speaker, Topik, Play</p>
                    </div>

                    <input type="file" id="fileInput" name="file" accept=".csv" required>
                    
                    <div id="fileName" style="color: #667eea; margin-bottom: 15px; font-weight: 500;"></div>

                    <button type="submit" class="btn-upload">
                        <i class="fas fa-upload"></i> Analisis Dataset
                    </button>
                </form>

                <div id="message"></div>
            </div>

            <!-- INFO SECTION -->
            <div class="info-section">
                <h3>ℹ️ Format File CSV</h3>
                <div class="info-cards">
                    <div class="info-card">
                        <i class="fas fa-database"></i>
                        <h4>Kolom Wajib</h4>
                        <p>No, Peserta, Budget, Speaker, Topik, Play</p>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-list-ul"></i>
                        <h4>Peserta</h4>
                        <p>&lt; 50 atau ≥ 50</p>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-money-bill"></i>
                        <h4>Budget</h4>
                        <p>Rendah atau Tinggi</p>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-microphone"></i>
                        <h4>Speaker</h4>
                        <p>Biasa atau Expert</p>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-tags"></i>
                        <h4>Topik</h4>
                        <p>Trending atau Niche</p>
                    </div>
                    <div class="info-card">
                        <i class="fas fa-check-circle"></i>
                        <h4>Play</h4>
                        <p>yes (Sukses) atau no (Gagal)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <p>⭐ Decision Tree ID3 - Algoritma Pembelajaran Mesin untuk Klasifikasi Data</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // File input display
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            const fileNameDiv = document.getElementById('fileName');
            
            if (fileName) {
                fileNameDiv.innerHTML = `<i class="fas fa-check-circle"></i> File dipilih: <strong>${fileName}</strong>`;
            }
        });

        // Drag and drop
        const uploadArea = document.querySelector('.upload-area');
        
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#764ba2';
            uploadArea.style.background = 'linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2))';
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.borderColor = '#667eea';
            uploadArea.style.background = 'linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05))';
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#667eea';
            uploadArea.style.background = 'linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05))';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('fileInput').files = files;
                const fileName = files[0].name;
                document.getElementById('fileName').innerHTML = `<i class="fas fa-check-circle"></i> File dipilih: <strong>${fileName}</strong>`;
            }
        });

        // Form submission error handling
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            const file = document.getElementById('fileInput').files[0];
            if (!file) {
                e.preventDefault();
                const messageDiv = document.getElementById('message');
                messageDiv.innerHTML = '<div class="file-info error"><i class="fas fa-exclamation-circle"></i> Pilih file terlebih dahulu!</div>';
            }
        });
    </script>
</body>
</html>