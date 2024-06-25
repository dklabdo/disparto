<?php
$servername = "localhost";
$username = "root";  // Remplacer par votre nom d'utilisateur de base de données
$password = "";      // Remplacer par votre mot de passe de base de données
$dbname = "project"; // Remplacer par le nom de votre base de données

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Requêtes pour obtenir les statistiques
$query_users = "SELECT COUNT(*) AS nombre_utilisateurs FROM users";
$query_messages = "SELECT COUNT(*) AS nombre_messages FROM contact_messages";


$result_users = $conn->query($query_users);
$result_messages = $conn->query($query_messages);

if ($result_users->num_rows > 0) {
    $row = $result_users->fetch_assoc();
    $nombre_utilisateurs = $row['nombre_utilisateurs'];
} else {
    $nombre_utilisateurs = 0;
}

if ($result_messages->num_rows > 0) {
    $row = $result_messages->fetch_assoc();
    $nombre_messages = $row['nombre_messages'];
} else {
    $nombre_messages = 0;
}

// Fermeture de la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Administratives</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; }
        .chart-container { width: 50%; margin: 0 auto; }
    </style>
</head>
<body>
    <h1>Administrative Statistics</h1>
    <div class="chart-container">
        <canvas id="barChart"></canvas>
    </div>
    <div class="chart-container">
        <canvas id="pieChart"></canvas>
    </div>

    <script>
        var barCtx = document.getElementById('barChart').getContext('2d');
        var pieCtx = document.getElementById('pieChart').getContext('2d');

        var barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Utilisateurs', 'Messages'],
                datasets: [{
                    label: 'Nombre',
                    data: [
                        <?php echo $nombre_utilisateurs; ?>, 
                        <?php echo $nombre_messages; ?>, 
                       
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Utilisateurs', 'Messages'],
                datasets: [{
                    label: 'Nombre',
                    data: [
                        <?php echo $nombre_utilisateurs; ?>, 
                        <?php echo $nombre_messages; ?>, 
                       
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });
    </script>
</body>
</html>
