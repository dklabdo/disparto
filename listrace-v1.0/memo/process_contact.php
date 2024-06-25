<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Connexion à la base de données
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connexion échouée: " . $conn->connect_error);
    }

    // Insertion du message dans la base de données
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $message);

    if ($stmt->execute()) {
        echo '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Message envoyé</title>
            <link href="css/style.css" rel="stylesheet">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .message-container {
                    background: #F8F8FD;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    text-align: center;
                }
                .message-container h2 {
                    color: #080E42;
                }
                .message-container button {
                    padding: 10px 20px;
                    font-size: 18px;
                    color: #F8F8FD;
                    background: #080E42;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    transition: background 0.3s;
                }
                .message-container button:hover {
                    background: #F8F8FD;
                    color: #080E42;
                }
            </style>
        </head>
        <body>
            <div class="message-container">
                <h2>Votre message a été envoyé avec succès.</h2>
                <button onclick="window.location.href=\'acceuil.html\'">Accueil</button>
            </div>
        </body>
        </html>';
    } else {
        echo "Une erreur est survenue. Veuillez réessayer plus tard.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Méthode de requête non autorisée.";
}
?>
