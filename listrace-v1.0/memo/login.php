<?php
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
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        // Utilisateur trouvé
        $user = $result->fetch_assoc();
        $stored_password_hash = $user['password'];
        $is_admin = $user['admin']; // Vérifie si l'utilisateur est un administrateur
        
        // Debug
        echo "Stored password hash: " . $stored_password_hash . "<br>";
        
        // Vérification du mot de passe et du statut admin
        if (password_verify($password, $stored_password_hash) && $is_admin) {
            // Mot de passe correct et utilisateur est admin
            header("Location:http://localhost/fifi/STARTUP/listrace-v1.0/memo/cdsms/admin/index.php"); // Rediriger vers la page admin.php
            exit();
        } elseif (password_verify($password, $stored_password_hash)) {
            // Mot de passe correct mais utilisateur n'est pas admin
            header("Location: acceuil.html"); // Rediriger vers la page d'accueil utilisateur normal
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
}

$conn->close();
?>
