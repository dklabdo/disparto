<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (full_name, phone, email, password) VALUES ('$full_name', '$phone', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        // Redirection vers acceuil.html après une inscription réussie
        header('Location: acceuil.html');
        exit(); // Assurez-vous d'appeler exit après la redirection
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
