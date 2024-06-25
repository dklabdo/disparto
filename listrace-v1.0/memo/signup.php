<?php
session_start(); // Démarrage de la session

// Connexion à la base de données MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Traitement des données d'inscription
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification si l'email existe déjà dans la base de données
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Cet email est déjà utilisé. Veuillez utiliser un autre email.')</script>";
        echo "<script>window.open('./login.html','_self')</script>";
    } else {
        // Vérification si le mot de passe et la confirmation du mot de passe correspondent
        if ($password !== $confirm_password) {
            echo "<script>alert('Le mot de passe et la confirmation du mot de passe ne correspondent pas.')</script>";
            echo "<script>window.open('./login.html','_self')</script>";
        } else {
            // Chiffrage du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Vérification s'il s'agit du premier utilisateur (administrateur)
            $is_admin = 0;
            $user_count_query = "SELECT COUNT(*) as user_count FROM users";
            $user_count_result = $conn->query($user_count_query);
            if ($user_count_result->num_rows == 1) {
                $row = $user_count_result->fetch_assoc();
                if ($row['user_count'] == 0) {
                    $is_admin = 1;
                }
            }

            // Insérer les données dans la table "users"
            $insert_query = "INSERT INTO users (username, email, password, admin) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("sssi", $username, $email, $hashed_password, $is_admin);

            if ($stmt->execute() === TRUE) {
                // Enregistrement effectué avec succès
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['is_admin'] = $is_admin;

                header("Location: acceuil.html");
                exit();
            } else {
                echo "Erreur lors de l'enregistrement : " . $stmt->error;
            }
        }
    }

    $stmt->close();
}

// Fermer la connexion à la base de données
$conn->close();
?>
