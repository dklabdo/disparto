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

$meta = []; // Initialisation du tableau $meta

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $user = $conn->query("SELECT * FROM contact_messages WHERE id = '{$_GET['id']}'");
    if ($user && $user->num_rows > 0) {
        $meta = $user->fetch_array(MYSQLI_ASSOC);
    }
}
?>

<div class="profile-card">
    <div class="profile-header">
        <h2><?php echo $meta['username']; ?></h2>
    </div>
    <div class="profile-body">
        <p><strong>Message:</strong> <?php echo $meta['message']; ?></p>
        <p><strong>Answer:</strong> <?php echo $meta['answer']; ?></p>
    </div>
</div>

<style>
.profile-card {
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 20px;
    max-width: 600px;
    margin: 0 auto;
}
.profile-header {
    text-align: center;
    margin-bottom: 20px;
}
.profile-body {
    text-align: left;
}
</style>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $answer = $conn->real_escape_string($_POST['answer']);

    // Exécuter la requête SQL pour enregistrer la réponse dans la base de données
    $sql = "UPDATE contact_messages SET answer = '$answer' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Answer sent successfully!";
        echo 1;
    } else {
        echo 0;
    }
    $conn->close();
    exit;
}
?>