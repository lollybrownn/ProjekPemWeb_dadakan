<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = $conn->real_escape_string($_POST['email']);
    $password_input = $_POST['password'];

    $sql = "SELECT id, email, password, nama, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password_input, $user['password'])) {
            
            // Set session umum
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

            // Handle Remember Me
            if (isset($_POST['remember']) && $_POST['remember'] == '1') {
                // Set cookie untuk 30 hari
                $cookie_time = time() + (86400 * 30); // 86400 detik = 1 hari
                
                // Simpan email
                setcookie('remember_email', $email, $cookie_time, '/');
                
                // Simpan token unik untuk keamanan (jangan simpan password asli!)
                $remember_token = bin2hex(random_bytes(32)); // Generate token random
                setcookie('remember_token', $remember_token, $cookie_time, '/');
                
                // Simpan token ke database untuk validasi nanti
                $update_token = "UPDATE users SET remember_token = ? WHERE id = ?";
                $stmt_token = $conn->prepare($update_token);
                $stmt_token->bind_param("si", $remember_token, $user['id']);
                $stmt_token->execute();
                $stmt_token->close();
                
            } else {
                // Hapus cookie jika user tidak centang remember me
                if (isset($_COOKIE['remember_email'])) {
                    setcookie('remember_email', '', time() - 3600, '/');
                }
                if (isset($_COOKIE['remember_token'])) {
                    setcookie('remember_token', '', time() - 3600, '/');
                }
                
                // Hapus token dari database
                $clear_token = "UPDATE users SET remember_token = NULL WHERE id = ?";
                $stmt_clear = $conn->prepare($clear_token);
                $stmt_clear->bind_param("i", $user['id']);
                $stmt_clear->execute();
                $stmt_clear->close();
            }

            // Arahkan berdasarkan role
            if ($user['role'] === 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $_SESSION['login_error'] = "Password salah!";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['login_error'] = "Email tidak ditemukan!";
        header("Location: login.php");
        exit;
    }
}

header("Location: login.php");
exit;
?>