<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Tracker Mahasiswa - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .task-card {
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 5px solid transparent;
        }
        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .deadline-urgent { border-left-color: #dc3545 !important; } 
        .deadline-near { border-left-color: #ffc107 !important; } 
        .deadline-safe { border-left-color: #0d6efd !important; } 
        .completed { opacity: 0.7; }
        .completed-border { border-left-color: #198754 !important; } 
        .progress-bar {
            transition: width 0.6s ease; 
        }
    </style>
</head>
<body>

    <div class="container my-5">
        <h1 class="text-center mb-4 text-primary">
            <i class="fas fa-clipboard-list me-2"></i> Assignment Tracker Mahasiswa
        </h1>

        <div id="auth-area" class="d-flex justify-content-center">
            <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
                <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login-content" type="button" role="tab">Login</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="register-tab" data-bs-toggle="pill" data-bs-target="#register-content" type="button" role="tab">Register</button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="login-content" role="tabpanel">
                        <h5 class="text-center mb-3">Login ke Akun Anda</h5>
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="login-username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="login-username" required>
                            </div>
                            <div class="mb-3">
                                <label for="login-password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="login-password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="register-content" role="tabpanel">
                        <h5 class="text-center mb-3">Buat Akun Baru</h5>
                        <form id="registerForm">
                            <div class="mb-3">
                                <label for="register-username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="register-username" required>
                            </div>
                            <div class="mb-3">
                                <label for="register-password" class="form-label">Password (Min. 6 Karakter)</label>
                                <input type="password" class="form-control" id="register-password" required minlength="6">
                            </div>
                            <button type="submit" class="btn btn-success w-100">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div id="main-app" class="d-none">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                 <p class="text-muted small mb-0" id="user-info">Login sebagai: Pengguna ID 0</p>
                 <button class="btn btn-sm btn-danger" onclick="handleLogout()">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </button>
            </div>
            
            <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="taskModalLabel">Tambah Tugas Baru</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="taskForm">
                            <input type="hidden" id="task-id">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Tugas</label>
                                    <input type="text" class="form-control" id="title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="course" class="form-label">Mata Kuliah</label>
                                    <input type="text" class="form-control" id="course" required>
                                </div>
                                <div class="mb-3">
                                    <label for="deadline" class="form-label">Deadline</label>
                                    <input type="date" class="form-control" id="deadline" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" required>
                                        <option value="Belum Selesai">Belum Selesai</option>
                                        <option value="Selesai">Selesai</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" id="saveTaskBtn">Simpan Tugas</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4 text-center">
                <div class="col-md-4">
                    <div class="card shadow-sm p-3 bg-white border-primary">
                        <div class="text-muted small">Total Tugas</div>
                        <h2 class="display-5 text-primary" id="total-tasks">0</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm p-3 bg-white border-success">
                        <div class="text-muted small">Selesai</div>
                        <h2 class="display-5 text-success" id="completed-tasks">0</h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm p-3 bg-white border-danger">
                        <div class="text-muted small">Belum Selesai</div>
                        <h2 class="display-5 text-danger" id="pending-tasks">0</h2>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm p-3 mb-5 bg-white">
                <h5 class="card-title text-muted mb-3">Progress Tugas Keseluruhan</h5>
                <div class="progress" style="height: 30px;">
                    <div id="mainProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-warning" 
                         role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        0% Selesai
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari Judul atau Mata Kuliah...">
                </div>
                <div class="col-md-3 mb-3">
                    <select id="statusFilter" class="form-select">
                        <option value="all">Semua Status</option>
                        <option value="Belum Selesai">Belum Selesai</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#taskModal" onclick="resetForm()">
                        <i class="fas fa-plus me-1"></i> Tambah Tugas
                    </button>
                </div>
            </div>

            <div id="taskList" class="row g-4">
                <p id="noTasksMessage" class="text-center text-muted p-5 d-none">Belum ada tugas yang dicatat. Mari tambahkan yang pertama!</p>
            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let allTasks = []; 
        let currentUserId = null; 


        function alertUser(message, type = 'primary') {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show fixed-bottom m-3" role="alert" style="z-index: 1050; max-width: 300px; margin-left: auto;">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('body').append(alertHtml);
            setTimeout(() => {
                $('.alert').alert('close');
            }, 3000);
        }

        function getDeadlineInfo(deadlineString) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            const deadline = new Date(deadlineString + 'T00:00:00');
            const diffTime = deadline.getTime() - today.getTime();
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            let status, badgeClass, cardClass;

            if (diffDays < 0) {
                status = 'Terlambat';
                cardClass = 'deadline-urgent';
                badgeClass = 'bg-danger';
            } else if (diffDays <= 3) {
                status = `Mendesak (${diffDays} hari)`;
                cardClass = 'deadline-urgent';
                badgeClass = 'bg-danger';
            } else if (diffDays <= 7) {
                status = `Dekat (${diffDays} hari)`;
                cardClass = 'deadline-near';
                badgeClass = 'bg-warning text-dark';
            } else {
                status = `Aman (${diffDays} hari)`;
                cardClass = 'deadline-safe';
                badgeClass = 'bg-primary';
            }

            return { status, cardClass };
        }

        function updateDashboard(tasks) {
            const total = tasks.length;
            const completed = tasks.filter(t => t.status === 'Selesai').length;
            const pending = total - completed;

            $('#total-tasks').text(total);
            $('#completed-tasks').text(completed);
            $('#pending-tasks').text(pending);

            const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;
            const $progressBar = $('#mainProgressBar');

            $progressBar.css('width', percentage + '%');
            $progressBar.attr('aria-valuenow', percentage);
            $progressBar.text(`${percentage}% Selesai (${completed}/${total})`);
            
            $progressBar.removeClass('bg-success bg-info bg-warning');
            if (percentage === 100) {
                $progressBar.addClass('bg-success'); 
            } else if (percentage >= 50) {
                $progressBar.addClass('bg-info'); 
            } else {
                $progressBar.addClass('bg-warning'); 
            }
        }

        function setAuthView(isLoggedIn, userId = null) {
            if (isLoggedIn) {
                $('#auth-area').addClass('d-none');
                $('#main-app').removeClass('d-none');
                currentUserId = userId;
                $('#user-info').text(`Login sebagai: User ID ${userId}`);
                fetchTasks();
                document.title = "Assignment Tracker Mahasiswa";
            } else {
                $('#auth-area').removeClass('d-none');
                $('#main-app').addClass('d-none');
                currentUserId = null;
                document.title = "Assignment Tracker Mahasiswa - Login";
            }
        }

        function handleRegister(event) {
            event.preventDefault();
            const username = $('#register-username').val();
            const password = $('#register-password').val();

            $.ajax({
                url: 'api.php',
                type: 'POST',
                data: JSON.stringify({ action: 'register', username, password }),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alertUser(response.message, 'success');
                        $('#registerForm')[0].reset();
                        bootstrap.Tab.getOrCreateInstance(document.getElementById('login-tab')).show();
                    } else {
                        alertUser('Gagal Register: ' + response.message, 'danger');
                    }
                },
                error: function() {
                    alertUser('Koneksi server gagal saat register.', 'danger');
                }
            });
        }

        function handleLogin(event) {
            event.preventDefault();
            const username = $('#login-username').val();
            const password = $('#login-password').val();

            $.ajax({
                url: 'api.php',
                type: 'POST',
                data: JSON.stringify({ action: 'login', username, password }),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alertUser(response.message, 'success');
                        $('#loginForm')[0].reset();
                        setAuthView(true, response.user_id);
                    } else {
                        alertUser('Gagal Login: ' + response.message, 'danger');
                    }
                },
                error: function() {
                    alertUser('Koneksi server gagal saat login.', 'danger');
                }
            });
        }
        
        window.handleLogout = function() {
            $.ajax({
                url: 'api.php?action=logout',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    alertUser(response.message, 'info');
                    setAuthView(false);
                    allTasks = []; 
                    renderTasks([]); 
                },
                error: function() {
                    alertUser('Koneksi server gagal saat logout.', 'danger');
                }
            });
        };

        function checkAuthStatus() {
            $.ajax({
                url: 'api.php?action=check_auth',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        setAuthView(true, response.user_id);
                    } else {
                        setAuthView(false);
                    }
                },
                error: function() {
                    console.warn("API check failed. Assuming not logged in.");
                    setAuthView(false);
                }
            });
        }

        function fetchTasks() {
            if (!currentUserId) return; 

            $.ajax({
                url: 'api.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        allTasks = response.tasks;
                        applyFilters(); 
                    } else {
                        if (response.message === 'Akses ditolak. Silakan login.') {
                            setAuthView(false);
                            alertUser('Sesi berakhir. Silakan login kembali.', 'warning');
                        } else {
                            alertUser('Gagal memuat tugas: ' + response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Fetch Error:", error);
                    alertUser('Koneksi server gagal. Cek config.php dan XAMPP Anda.', 'danger');
                }
            });
        }

        function saveTask(event) {
            event.preventDefault();
            const taskId = $('#task-id').val();
            const taskData = {
                title: $('#title').val().trim(),
                course: $('#course').val().trim(),
                deadline: $('#deadline').val(),
                status: $('#status').val(),
            };

            const type = taskId ? 'PUT' : 'POST';
            const url = 'api.php';
            
            if (taskId) {
                taskData.id = taskId;
            }

            $.ajax({
                url: url,
                type: type,
                data: JSON.stringify(taskData),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alertUser(response.message, 'success');
                        $('#taskModal').modal('hide');
                        fetchTasks(); 
                    } else {
                        alertUser('Gagal menyimpan tugas: ' + response.message, 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Save Error:", error);
                    alertUser('Koneksi server gagal saat menyimpan.', 'danger');
                }
            });
        }

        window.deleteTask = function(id) {
            if (!confirm("Apakah Anda yakin ingin menghapus tugas ini?")) {
                return;
            }

            $.ajax({
                url: 'api.php',
                type: 'DELETE',
                data: JSON.stringify({ id: id }),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alertUser(response.message, 'success');
                        fetchTasks(); 
                    } else {
                        alertUser('Gagal menghapus tugas: ' + response.message, 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Delete Error:", error);
                    alertUser('Koneksi server gagal saat menghapus.', 'danger');
                }
            });
        };

        window.toggleStatus = function(id, currentStatus) {
            const newStatus = currentStatus === 'Selesai' ? 'Belum Selesai' : 'Selesai';
            
            $.ajax({
                url: 'api.php',
                type: 'PUT',
                data: JSON.stringify({ id: id, status: newStatus }),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alertUser(`Status diubah menjadi: ${newStatus}`, 'info');
                        fetchTasks(); 
                    } else {
                        alertUser('Gagal mengubah status: ' + response.message, 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Toggle Status Error:", error);
                    alertUser('Koneksi server gagal saat mengubah status.', 'danger');
                }
            });
        };

        window.editTask = function(id) {
            const task = allTasks.find(t => t.id == id);
            if (!task) return;

            $('#taskModalLabel').text('Edit Tugas');
            $('#task-id').val(task.id);
            $('#title').val(task.title);
            $('#course').val(task.course);
            $('#deadline').val(task.deadline);
            $('#status').val(task.status);
            
            $('#taskModal').modal('show');
        };

        function renderTasks(tasksToRender) {
            const $taskList = $('#taskList');
            $taskList.empty();

            if (tasksToRender.length === 0) {
                $('#noTasksMessage').removeClass('d-none');
                return;
            }

            $('#noTasksMessage').addClass('d-none');
            
            tasksToRender.sort((a, b) => new Date(a.deadline) - new Date(b.deadline));

            tasksToRender.forEach(task => {
                const isCompleted = task.status === 'Selesai';
                const { status: deadlineStatus, cardClass: deadlineCardClass } = getDeadlineInfo(task.deadline);

                const cardClasses = isCompleted ? 'bg-success-subtle completed completed-border' : `bg-white ${deadlineCardClass}`;
                const titleClass = isCompleted ? 'text-decoration-line-through text-muted' : '';

                const taskHtml = `
                    <div class="col-md-6 col-lg-4">
                        <div class="task-card card shadow-sm h-100 ${cardClasses}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <input type="checkbox" id="check-${task.id}" class="form-check-input me-2" 
                                            ${isCompleted ? 'checked' : ''} 
                                            onclick="toggleStatus(${task.id}, '${task.status}')">
                                        <span class="fs-5 fw-bold ${titleClass}">${task.title}</span>
                                        <p class="card-text text-primary small mt-1">
                                            <i class="fas fa-book me-1"></i> ${task.course}
                                        </p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="editTask(${task.id})">
                                                <i class="fas fa-edit me-2"></i>Edit Detail
                                            </a></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteTask(${task.id})">
                                                <i class="fas fa-trash me-2"></i>Hapus
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Deadline: ${task.deadline}</small>
                                    <p class="mb-0 fw-bold small text-end ${isCompleted ? 'text-success' : (deadlineCardClass === 'deadline-urgent' ? 'text-danger' : 'text-dark')}">
                                        ${isCompleted ? 'Selesai' : deadlineStatus}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $taskList.append(taskHtml);
            });
        }

        window.applyFilters = function() {
            const searchQuery = $('#searchInput').val().toLowerCase();
            const filterStatus = $('#statusFilter').val();

            const filteredTasks = allTasks.filter(task => {
                
                const statusMatch = filterStatus === 'all' || task.status === filterStatus;

                const searchMatch = task.title.toLowerCase().includes(searchQuery) ||
                                    task.course.toLowerCase().includes(searchQuery);

                return statusMatch && searchMatch;
            });

            renderTasks(filteredTasks);
            updateDashboard(allTasks); 
        };

        window.resetForm = function() {
            $('#taskForm')[0].reset();
            $('#task-id').val('');
            $('#taskModalLabel').text('Tambah Tugas Baru');
            $('#status').val('Belum Selesai');
        }

        $(document).ready(function() {
            
            $('#registerForm').on('submit', handleRegister);
            $('#loginForm').on('submit', handleLogin);
            
            $('#taskForm').on('submit', saveTask);
            $('#searchInput').on('input', applyFilters);
            $('#statusFilter').on('change', applyFilters);

            checkAuthStatus();
        });

    </script>

</body>
</html>