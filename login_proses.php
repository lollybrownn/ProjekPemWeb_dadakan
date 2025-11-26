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
            $_SESSION['role'] = $user['role']; // ini kuncinya!

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