<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data
    $nama = $conn->real_escape_string($_POST['nama']);
    $email = $conn->real_escape_string($_POST['email']);
    $password_input = $_POST['password'];

    // Validasi Sederhana
    if (strlen($password_input) < 6) {
        $_SESSION['registration_error'] = "Password minimal 6 karakter.";
        header("Location: register.php");
        exit;
    }

    // 1. Hashing Password (PENTING untuk keamanan)
    $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);

    // 2. Cek apakah email sudah terdaftar
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $_SESSION['registration_error'] = "Email ini sudah terdaftar. Silakan gunakan email lain atau Sign In.";
        header("Location: register.php");
        exit;
    }
    $stmt_check->close();

    // 3. Masukkan data pengguna baru
    $insert_sql = "INSERT INTO users (nama, email, password) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_sql);
    $stmt_insert->bind_param("sss", $nama, $email, $hashed_password);

    if ($stmt_insert->execute()) {
        
        // Registrasi Berhasil! Arahkan ke login dengan pesan sukses
        $_SESSION['registration_success'] = "Akun berhasil dibuat! Silakan Sign In.";
        header("Location: login.php"); 
        exit;
    } else {
        // Gagal menyimpan ke database
        $_SESSION['registration_error'] = "Registrasi Gagal. Silakan coba lagi. Error: " . $stmt_insert->error;
        header("Location: register.php"); 
        exit;
    }

    $stmt_insert->close();
}

$conn->close();

// Jika diakses tanpa POST, arahkan kembali ke register
header("Location: register.php");
exit;
?>