<?php
header('Content-Type: application/json');

include 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$response = ['success' => false, 'message' => 'Invalid Request'];

$input = json_decode(file_get_contents('php://input'), true);

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'check_auth') {
        if (isset($_SESSION['user_id'])) {
            $response = ['success' => true, 'message' => 'Authenticated', 'user_id' => $_SESSION['user_id']];
        } else {
            $response = ['success' => false, 'message' => 'Not authenticated'];
        }
    } elseif ($action === 'logout') {
        session_unset();
        session_destroy();
        $response = ['success' => true, 'message' => 'Logout berhasil.'];
    }

    echo json_encode($response);
    $conn->close();
    exit;
}

if ($method === 'POST' && isset($input['action'])) {
    if ($input['action'] === 'register') {
        $username = $conn->real_escape_string($input['username']);
        $password = $input['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if (!empty($username) && strlen($password) >= 6) {
            $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
            
            if ($conn->query($sql) === TRUE) {
                $response = ['success' => true, 'message' => 'Registrasi berhasil! Silakan Login.'];
            } else {
                $response = ['success' => false, 'message' => 'Username sudah terdaftar atau error database: ' . $conn->error];
            }
        } else {
            $response = ['success' => false, 'message' => 'Username/Password tidak valid. (Min 6 karakter)'];
        }

    } elseif ($input['action'] === 'login') {
        $username = $conn->real_escape_string($input['username']);
        $password = $input['password'];

        $sql = "SELECT id, password FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $response = ['success' => true, 'message' => 'Login berhasil!', 'user_id' => $user['id']];
            } else {
                $response = ['success' => false, 'message' => 'Password salah.'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Username tidak ditemukan.'];
        }
    }
    echo json_encode($response);
    $conn->close();
    exit;
}

if (!isset($_SESSION['user_id'])) {
    $response = ['success' => false, 'message' => 'Akses ditolak. Silakan login.'];
    echo json_encode($response);
    $conn->close();
    exit;
}

$user_id = $_SESSION['user_id'];

if ($method === 'GET') {
    $sql = "SELECT id, title, course, deadline, status FROM assignments WHERE user_id = $user_id ORDER BY deadline ASC";
    $result = $conn->query($sql);
    $tasks = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
    }
    $response = ['success' => true, 'tasks' => $tasks];

} elseif ($method === 'POST') {
    $title = $conn->real_escape_string($input['title']);
    $course = $conn->real_escape_string($input['course']);
    $deadline = $conn->real_escape_string($input['deadline']);
    $status = $conn->real_escape_string($input['status']);

    if (!empty($title) && !empty($course) && !empty($deadline)) {
        $sql = "INSERT INTO assignments (user_id, title, course, deadline, status) VALUES ($user_id, '$title', '$course', '$deadline', '$status')";
        
        if ($conn->query($sql) === TRUE) {
            $response = ['success' => true, 'message' => 'Tugas baru berhasil ditambahkan!'];
        } else {
            $response = ['success' => false, 'message' => 'Error: ' . $conn->error];
        }
    } else {
        $response = ['success' => false, 'message' => 'Data input tugas tidak lengkap.'];
    }

} elseif ($method === 'PUT') {
    $id = intval($input['id']);

    if ($id > 0) {
        $set_clauses = [];
        
        if (isset($input['status'])) { $set_clauses[] = "status = '" . $conn->real_escape_string($input['status']) . "'"; }
        if (isset($input['title'])) { $set_clauses[] = "title = '" . $conn->real_escape_string($input['title']) . "'"; }
        if (isset($input['course'])) { $set_clauses[] = "course = '" . $conn->real_escape_string($input['course']) . "'"; }
        if (isset($input['deadline'])) { $set_clauses[] = "deadline = '" . $conn->real_escape_string($input['deadline']) . "'"; }

        if (!empty($set_clauses)) {
            $sql = "UPDATE assignments SET " . implode(', ', $set_clauses) . " WHERE id = $id AND user_id = $user_id";
            if ($conn->query($sql) === TRUE) {
                if ($conn->affected_rows > 0) {
                    $response = ['success' => true, 'message' => 'Tugas berhasil diperbarui!'];
                } else {
                    $response = ['success' => false, 'message' => 'Tugas tidak ditemukan atau bukan milik Anda.'];
                }
            } else {
                $response = ['success' => false, 'message' => 'Error: ' . $conn->error];
            }
        } else {
             $response = ['success' => false, 'message' => 'Tidak ada data untuk diperbarui.'];
        }
    } else {
        $response = ['success' => false, 'message' => 'ID Tugas tidak valid.'];
    }
} elseif ($method === 'DELETE') {
    $id = intval($input['id']);

    if ($id > 0) {
        $sql = "DELETE FROM assignments WHERE id = $id AND user_id = $user_id";
        if ($conn->query($sql) === TRUE) {
            if ($conn->affected_rows > 0) {
                 $response = ['success' => true, 'message' => 'Tugas berhasil dihapus.'];
            } else {
                $response = ['success' => false, 'message' => 'Tugas tidak ditemukan atau bukan milik Anda.'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Error: ' . $conn->error];
        }
    } else {
        $response = ['success' => false, 'message' => 'ID Tugas tidak valid.'];
    }
}

$conn->close();
echo json_encode($response);
?>