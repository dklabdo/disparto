<?php
// Inclure le fichier de configuration
include('includes/config.php');

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

// Marquer un message comme lu
if (isset($_POST['mark_as_read'])) {
    $message_id = intval($_POST['message_id']);
    $update_query = "UPDATE contact_messages SET is_read = 1 WHERE id = $message_id";
    if (!$conn->query($update_query)) {
        die("Erreur lors de la mise à jour : " . $conn->error);
    }
}

// Supprimer un message
if (isset($_POST['delete_message'])) {
    $message_id = intval($_POST['message_id']);
    $delete_query = "DELETE FROM contact_messages WHERE id = $message_id";
    if (!$conn->query($delete_query)) {
        die("Erreur lors de la suppression du message : " . $conn->error);
    }
    // Rediriger vers la page actuelle pour actualiser la liste des messages
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Requête pour récupérer tous les messages, y compris les réponses
$query = "SELECT u.username, cm.id, cm.message, cm.answer, cm.is_read FROM users u JOIN contact_messages cm ON u.email = cm.email";
$result = $conn->query($query);

if (!$result) {
    die("Erreur de requête : " . $conn->error);
}

// Fermeture de la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User answer</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0px; }
        h1 { text-align: center; }
        .message { margin-bottom: 50px; }
        .read { background-color: #f0f0f0; } /* Style pour les messages lus */
        nav { background-color: #010429; color: #040955; padding: 10px; text-align: center; }
        nav a { color: #fff; text-decoration: none; margin: 0 10px; }
    </style>
</head>
<body>
    <nav>
        <a href="acceuil.html">Home</a>
        <a href="contact.html">Contact Us</a>
    </nav>
    <h1>Messages Notification</h1>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="message<?php echo $row['is_read'] == 1 ? ' read' : ''; ?>">
                <p><strong>Name of Candidat :</strong> <?php echo htmlspecialchars($row['username']); ?></p>
                <p><strong> the Message :</strong> <?php echo htmlspecialchars($row['message']); ?></p>
                <p><strong> the Answer :</strong> <?php echo htmlspecialchars($row['answer']); ?></p>
                <?php if ($row['is_read'] == 0): ?>
                    <form action="" method="post">
                        <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                        <input type="submit" name="mark_as_read" value="Marquer comme lu">
                    </form>
                <?php endif; ?>
                <form action="" method="post">
                    <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                    <input type="submit" name="delete_message" value="Supprimer">
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Aucune réponse trouvée pour cet utilisateur.</p>
    <?php endif; ?>
</body>
</html>
