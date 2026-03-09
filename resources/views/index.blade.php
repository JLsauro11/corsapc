<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Information Form</title>
    <!-- Favicon using rs8-logo.png -->
    <link rel="icon" type="image/png" href="{{ asset('assets/rs8-logo.png') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Add SweetAlert2 CDN before closing </head> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #ea4d4d;
        }
        .bg-primary-custom {
            background-color: var(--primary-color) !important;
        }
        .text-primary-custom {
            color: var(--primary-color) !important;
        }
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #ffffff;
        }
        .btn-primary-custom:hover {
            background-color: #d93f3f;
            border-color: #d93f3f;
            color: #ffffff;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(234, 77, 77, 0.25);
        }
        /* Updated select focus state */
        .form-select:focus {
            border-color: #ea4d4d;
            outline: 0;
            box-shadow: 0 0 0 .25rem rgba(234, 77, 77, .25);
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary-custom text-white px-5 py-1">

                <div class="d-flex align-items-center">
                        <!-- BIG Logo on LEFT - Vertically centered -->
                        <img src="{{ asset('assets/rs8-logo.png') }}" alt="RS8 Logo" class="" style="height: 150px; width: auto;">
                        <!-- Title CENTERED -->
                        <div class="flex-grow-1 text-center mx-auto">
                            <h1 class="mb-2 display-6"><i class="bi bi-car-front me-2"></i>Vehicle Certificate Form</h1>
                            <p class="mb-0 opacity-75">Please fill in all vehicle details accurately</p>
                        </div>
                    </div>
                </div>


                <div class="card-body p-5">
                    <form id="vehicleForm" method="POST" action="{{ route('vehicle.pdf.generate') }}">
                    @csrf
                        <!-- Compatible Vehicle Model -->
                        <div class="row mb-4">
                            <label for="vehicleModel" class="form-label fw-bold">
                                <i class="bi bi-car-front-fill text-primary-custom me-2"></i>Compatible Vehicle Model
                            </label>
                            <input type="text" class="form-control form-control-lg" id="vehicleModel"
                                   name="vehicleModel" placeholder="e.g., Toyota Corolla 2023" required>
                            <div class="form-text">Enter the exact vehicle model name</div>
                        </div>

                        <!-- Engine Type -->
                        <div class="row mb-4">
                            <label for="engineType" class="form-label fw-bold">
                                <i class="bi bi-gear-fill text-primary-custom me-2"></i>Engine Type
                            </label>
                            <select class="form-select form-select-lg" id="engineType" name="engineType" required>
                                <option value="">Choose engine type...</option>
                                <option value="gasoline">Gasoline</option>
                                <option value="diesel">Diesel</option>
                                <option value="hybrid">Hybrid</option>
                                <option value="electric">Electric</option>
                                <option value="turbo">Turbo</option>
                            </select>
                        </div>

                        <!-- Chassis Number -->
                        <div class="row mb-4">
                            <label for="chassisNumber" class="form-label fw-bold">
                                <i class="bi bi-key text-primary-custom me-2"></i>Chassis Number
                            </label>
                            <input type="text" class="form-control form-control-lg" id="chassisNumber"
                                   name="chassisNumber" placeholder="e.g., ABC123DEF456" maxlength="17" required>
                            <div class="form-text">17-character VIN/Chassis number (no spaces)</div>
                        </div>

                        <!-- Engine Number -->
                        <div class="row mb-4">
                            <label for="engineNumber" class="form-label fw-bold">
                                <i class="bi bi-fuel-pump text-primary-custom me-2"></i>Engine Number
                            </label>
                            <input type="text" class="form-control form-control-lg" id="engineNumber"
                                   name="engineNumber" placeholder="e.g., E123456789" maxlength="12" required>
                            <div class="form-text">Enter the complete engine serial number</div>
                        </div>

                        <!-- Pipe Type (NEW) -->
                        <div class="row mb-4">
                            <label for="pipeType" class="form-label fw-bold">
                                <i class="bi bi-pipe text-primary-custom me-2"></i>Pipe Type
                            </label>
                            <select class="form-select form-select-lg" id="pipeType" name="pipeType" required>
                                <option value="">Choose pipe type...</option>
                                <option value="redlak">Redlak</option>
                                <option value="dc8">DC8</option>
                            </select>
                            <div class="form-text">Select the pipe type for correct template</div>
                        </div>


                        <!-- Submit Button -->
                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-primary-custom btn-lg fw-bold py-3">
                                <i class="bi bi-download me-2"></i>Generate & Download RS8 Certificate
                            </button>
                            <button type="reset" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset Form
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Same JavaScript as before -->
<!-- DELETE ALL old JavaScript, replace with this -->
<script>
    document.getElementById('vehicleForm').addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
                title: 'Generating Certificate...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
    });

        const formData = new FormData(this);

        // STEP 1: Validate (JSON)
        $.ajax({
            url: "{{ route('vehicle.pdf.validate') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function() {
                Swal.close();
                downloadPdf(formData);
            },
            error: function(xhr) {
                Swal.close();
                let errorMessage = 'Something went wrong!';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.status === 500 && xhr.responseJSON?.error) {
                    errorMessage = xhr.responseJSON.error;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: errorMessage
                });
            }
        });
    });

    function downloadPdf(formData) {
        Swal.fire({
                title: 'Downloading Certificate...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
    });

        // STEP 2: PDF Download (blob)
        $.ajax({
            url: "{{ route('vehicle.pdf.generate') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhrFields: { responseType: 'blob' },
            success: function(response) {
                Swal.fire('Success!', 'RS8 Certificate downloaded!', 'success');

                const blob = new Blob([response], { type: 'application/pdf' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'RS8-LTO-Certificate.pdf';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            },
            error: function() {
                Swal.fire('Error!', 'Download failed!', 'error');
            }
        });
    }
</script>





</body>
</html>
