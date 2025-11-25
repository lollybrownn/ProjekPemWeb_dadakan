<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = $conn->real_escape_string($_POST['email']);
    $password_input = $_POST['password'];

    // Gunakan Prepared Statement untuk keamanan (mencegah SQL Injection)
    $sql = "SELECT id, email, password, nama FROM users WHERE email = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verifikasi password dengan hash yang tersimpan di database
        if (password_verify($password_input, $user['password'])) {
            
            // Login Berhasil! Atur sesi
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nama'] = $user['nama'];
            
            header("Location: dashboard.php"); 
            exit;
        } else {
            // Password salah
            $_SESSION['login_error'] = "Email atau Password salah.";
            header("Location: login.php");
            exit;
        }
    } else {
        // Pengguna tidak ditemukan
        $_SESSION['login_error'] = "Email atau Password salah.";
        header("Location: login.php");
        exit;
    }

    $stmt->close();
}

$conn->close();

// Jika diakses tanpa POST, arahkan kembali ke login
header("Location: login.php");
exit;
?>