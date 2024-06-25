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

// Traitement des données de connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Recherche de l'utilisateur dans la base de données
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Utilisateur trouvé
        $user = $result->fetch_assoc();
        $stored_password_hash = $user['password'];
        $is_admin = $user['admin']; // Vérifie si l'utilisateur est un administrateur
        
        // Vérification du mot de passe et du statut admin
        if (password_verify($password, $stored_password_hash)) {
            // Mot de passe correct
            $_SESSION['email'] = $email; // Stocke l'e-mail de l'utilisateur dans la session
            $_SESSION['is_admin'] = (bool)$is_admin; // Indique que l'utilisateur est un admin ou non
            
            if ($is_admin) {
                header("Location: cdsms/admin/index.php"); // Rediriger vers la page admin
            } else {
                header("Location: acceuil.php"); // Rediriger vers la page d'accueil utilisateur normal
            }
            exit();
        } else {
            // Mot de passe incorrect
            header("Location: erreur.html");
            exit();
        }
    } else {
        // Utilisateur non trouvé
        header("Location: erreur.html");
        exit();
    }
    $stmt->close();
}

$conn->close();
?>
