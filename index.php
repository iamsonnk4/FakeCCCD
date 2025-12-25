<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo CCCD Giả</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: #fff;
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        .container {
            max-width: 800px;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .card-header {
            background: #303030ff;
            color: white;
            padding: 2rem;
            border: none;
        }
        
        .card-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 1.75rem;
        }
        
        .card-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 2rem;
        }
        
        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-tabs .nav-link:hover {
            color: #667eea;
            border: none;
        }
        
        .nav-tabs .nav-link.active {
            color: #667eea;
            background: transparent;
            border: none;
            font-weight: 600;
        }
        
        .nav-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: #303030ff;
            border-radius: 3px 3px 0 0;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
        
        .form-control::placeholder {
            color: #a0aec0;
        }
        
        .btn-generate {
            background: #303030ff;
            color: white;
            border: none;
            padding: 1rem 3rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            width: 100%;
            margin-top: 1.5rem;
        }
        
        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }
        
        .btn-generate:active {
            transform: translateY(0);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        textarea.form-control {
            resize: vertical;
        }
        
        /* Loading spinner */
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 2rem;
        }
        
        .loading-spinner.active {
            display: block;
        }
        
        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3rem;
        }
        
        /* Results section */
        .results-section {
            display: none;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e9ecef;
        }
        
        .results-section.active {
            display: block;
        }
        
        .result-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .image-container {
            margin-bottom: 2rem;
        }
        
        .image-container h4 {
            font-weight: 600;
            color: #667eea;
            margin-bottom: 1rem;
        }
        
        .image-container img {
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .image-container img:hover {
            transform: scale(1.02);
        }
        
        .alert-error {
            background-color: #fee;
            border: 2px solid #fcc;
            color: #c33;
            padding: 1rem;
            border-radius: 10px;
            margin-top: 1rem;
        }
        
        .btn-download {
            margin-top: 0.5rem;
            background: #28a745;
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-download:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        /* Photo upload styles */
        .photo-upload-container {
            margin-top: 1rem;
            padding: 1rem;
            border: 2px dashed #e9ecef;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .photo-upload-container:hover {
            border-color: #667eea;
            background: #f8f9fa;
        }
        
        .photo-preview {
            max-width: 150px;
            max-height: 200px;
            margin: 1rem auto;
            display: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .photo-preview.active {
            display: block;
        }
        
        .input-group-small {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .input-group-small input {
            padding: 0.5rem;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>🆔 Tạo Ảnh CCCD 2 Mặt</h2>
                <p>Điền thông tin để tạo ảnh Căn cước công dân giả</p>
            </div>
            <div class="card-body">
                <form id="cccdForm" method="POST" action="">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" id="cccdTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab" data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">
                                Thông tin cá nhân
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab2-tab" data-bs-toggle="tab" data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false">
                                Thông tin bổ sung
                            </button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="cccdTabsContent">
                        <!-- Tab 1: Thông tin cá nhân -->
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                            <div class="form-group">
                                <label for="sodinhdanh" class="form-label">Số định danh cá nhân</label>
                                <input type="text" class="form-control" id="sodinhdanh" name="sodinhdanh" placeholder="Nhập số định danh 12 số" maxlength="12" required>
                            </div>

                            <div class="form-group">
                                <label for="hoten" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="hoten" name="hoten" placeholder="Nhập họ và tên đầy đủ" required>
                            </div>

                            <div class="form-group">
                                <label for="ngaysinh" class="form-label">Ngày tháng năm sinh</label>
                                <input type="date" class="form-control" id="ngaysinh" name="ngaysinh" required>
                            </div>

                            <div class="form-group">
                                <label for="gioitinh" class="form-label">Giới tính</label>
                                <select class="form-select" id="gioitinh" name="gioitinh" required>
                                    <option value="">Chọn giới tính</option>
                                    <option value="Nam">Nam</option>
                                    <option value="Nữ">Nữ</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="quoctich" class="form-label">Quốc tịch</label>
                                <input type="text" class="form-control" id="quoctich" name="quoctich" value="Việt Nam" required>
                            </div>
                            
                            <!-- Photo Upload Section -->
                            <div class="form-group">
                                <label class="form-label">📸 Ảnh thẻ (cho mặt trước)</label>
                                <div class="photo-upload-container">
                                    <input type="file" class="form-control" id="photoUpload" accept="image/*">
                                    <img id="photoPreview" class="photo-preview" src="" alt="Preview">
                                    
                                    <div class="mt-3">
                                        <label class="form-label" style="font-size: 0.9rem;">Vị trí & Kích thước ảnh</label>
                                        <div class="input-group-small">
                                            <input type="number" class="form-control" id="photoX" placeholder="X (px)" value="140">
                                            <input type="number" class="form-control" id="photoY" placeholder="Y (px)" value="380">
                                            <input type="number" class="form-control" id="photoWidth" placeholder="Width (px)" value="300">
                                            <input type="number" class="form-control" id="photoHeight" placeholder="Height (px)" value="435">
                                        </div>
                                        <small class="text-muted">Điều chỉnh vị trí (X, Y) và kích thước (Width, Height) của ảnh trên thẻ</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2: Thông tin bổ sung -->
                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                            <div class="form-group">
                                <label for="noicutru" class="form-label">Nơi cư trú</label>
                                <input type="text" class="form-control" id="noicutru" name="noicutru" placeholder="Nhập địa chỉ nơi cư trú" required>
                            </div>

                            <div class="form-group">
                                <label for="noidangkykhaisinh" class="form-label">Nơi đăng ký khai sinh</label>
                                <input type="text" class="form-control" id="noidangkykhaisinh" name="noidangkykhaisinh" placeholder="Nhập nơi đăng ký khai sinh" required>
                            </div>

                            <div class="form-group">
                                <label for="ngaycap" class="form-label">Ngày tháng năm cấp</label>
                                <input type="date" class="form-control" id="ngaycap" name="ngaycap" required>
                            </div>

                            <div class="form-group">
                                <label for="ngayhethan" class="form-label">Ngày tháng năm hết hạn</label>
                                <input type="date" class="form-control" id="ngayhethan" name="ngayhethan" required>
                            </div>

                            <div class="form-group">
                                <label for="idvnm_code" class="form-label">IDVNM Code</label>
                                <textarea class="form-control" id="idvnm_code" name="idvnm_code" rows="3" placeholder="Nhập IDVNM Code" required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-generate" id="submitBtn">
                        🎨 Tạo ảnh CCCD 2 mặt
                    </button>
                </form>
                
                <!-- Loading Spinner -->
                <div class="loading-spinner" id="loadingSpinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang xử lý...</span>
                    </div>
                    <p class="mt-3">Đang tạo ảnh CCCD, vui lòng đợi...</p>
                </div>
                
                <!-- Results Section -->
                <div class="results-section" id="resultsSection">
                    <h3 class="result-title">✅ Ảnh CCCD đã được tạo thành công!</h3>
                    
                    <div class="image-container">
                        <h4>📄 Mặt trước (Front)</h4>
                        <img id="frontImage" src="" alt="CCCD Mặt Trước">
                        <button class="btn btn-download" onclick="downloadImage('frontImage', 'cccd-mat-truoc.png')">
                            ⬇️ Tải xuống mặt trước
                        </button>
                    </div>
                    
                    <div class="image-container">
                        <h4>📄 Mặt sau (Back)</h4>
                        <img id="backImage" src="" alt="CCCD Mặt Sau">
                        <button class="btn btn-download" onclick="downloadImage('backImage', 'cccd-mat-sau.png')">
                            ⬇️ Tải xuống mặt sau
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Photo preview functionality
        document.getElementById('photoUpload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('photoPreview');
                    preview.src = event.target.result;
                    preview.classList.add('active');
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Form submission with AJAX
        document.getElementById('cccdForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Basic validation for số định danh
            const sodinhdanh = document.getElementById('sodinhdanh').value;
            if (sodinhdanh.length !== 12 || !/^\d+$/.test(sodinhdanh)) {
                alert('Số định danh phải là 12 chữ số!');
                return;
            }
            
            // Get photo data if uploaded
            const photoPreview = document.getElementById('photoPreview');
            const photoData = photoPreview.classList.contains('active') ? photoPreview.src : '';
            
            // Get form data
            const formData = {
                // Tab 1 data
                sodinhdanh: document.getElementById('sodinhdanh').value,
                hoten: document.getElementById('hoten').value,
                ngaysinh: document.getElementById('ngaysinh').value,
                gioitinh: document.getElementById('gioitinh').value,
                quoctich: document.getElementById('quoctich').value,
                // Photo data
                photo: photoData,
                photoX: document.getElementById('photoX').value,
                photoY: document.getElementById('photoY').value,
                photoWidth: document.getElementById('photoWidth').value,
                photoHeight: document.getElementById('photoHeight').value,
                // Tab 2 data
                noicutru: document.getElementById('noicutru').value,
                noidangkykhaisinh: document.getElementById('noidangkykhaisinh').value,
                ngaycap: document.getElementById('ngaycap').value,
                ngayhethan: document.getElementById('ngayhethan').value,
                idvnm_code: document.getElementById('idvnm_code').value
            };
            
            // Show loading spinner
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('loadingSpinner').classList.add('active');
            document.getElementById('resultsSection').classList.remove('active');
            
            try {
                // Call both APIs in parallel
                const [frontResponse, backResponse] = await Promise.all([
                    generateFrontImage(formData),
                    generateBackImage(formData)
                ]);
                
                // Check if both responses are successful
                if (frontResponse.success && backResponse.success) {
                    // Display images
                    document.getElementById('frontImage').src = frontResponse.image;
                    document.getElementById('backImage').src = backResponse.image;
                    
                    // Show results section
                    document.getElementById('resultsSection').classList.add('active');
                    
                    // Scroll to results
                    document.getElementById('resultsSection').scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'nearest' 
                    });
                } else {
                    // Show error
                    const errorMsg = frontResponse.error || backResponse.error || 'Có lỗi xảy ra!';
                    alert('Lỗi: ' + errorMsg);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi tạo ảnh. Vui lòng thử lại!');
            } finally {
                // Hide loading spinner
                document.getElementById('loadingSpinner').classList.remove('active');
                document.getElementById('submitBtn').disabled = false;
            }
        });
        
        // Function to call generate_front.php API
        async function generateFrontImage(data) {
            const formData = new FormData();
            formData.append('sodinhdanh', data.sodinhdanh);
            formData.append('hoten', data.hoten);
            formData.append('ngaysinh', data.ngaysinh);
            formData.append('gioitinh', data.gioitinh);
            formData.append('quoctich', data.quoctich);
            
            // Add photo data if available
            if (data.photo) {
                formData.append('photo', data.photo);
                formData.append('photoX', data.photoX);
                formData.append('photoY', data.photoY);
                formData.append('photoWidth', data.photoWidth);
                formData.append('photoHeight', data.photoHeight);
            }
            
            const response = await fetch('generate_front.php', {
                method: 'POST',
                body: formData
            });
            
            return await response.json();
        }
        
        // Function to call generate_back.php API
        async function generateBackImage(data) {
            const formData = new FormData();
            formData.append('noicutru', data.noicutru);
            formData.append('noidangkykhaisinh', data.noidangkykhaisinh);
            formData.append('ngaycap', data.ngaycap);
            formData.append('ngayhethan', data.ngayhethan);
            formData.append('idvnm_code', data.idvnm_code);
            
            // Add data for QR code generation
            formData.append('sodinhdanh', data.sodinhdanh);
            formData.append('hoten', data.hoten);
            formData.append('ngaysinh', data.ngaysinh);
            formData.append('gioitinh', data.gioitinh);
            
            const response = await fetch('generate_back.php', {
                method: 'POST',
                body: formData
            });
            
            return await response.json();
        }
        
        // Function to download image
        function downloadImage(imageId, filename) {
            const image = document.getElementById(imageId);
            const link = document.createElement('a');
            link.href = image.src;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>
