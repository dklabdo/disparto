<?php 
// Connexion à la base de données MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Execute query and handle errors
function executeQuery($conn, $sql) {
    $result = $conn->query($sql);
    if ($result === false) {
        echo "Error executing query: " . $conn->error;
        return false;
    }
    return $result;
}

?>
<h1><?php echo $_settings->info('name') ?></h1>
<hr class="border-info">
<div class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-th-list"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">client Message</span>
                <span class="info-box-number text-right">
                    <?php 
                        $result = $conn->query("SELECT * FROM `contact_messages` ");
                        echo $result ? $result->num_rows : "Error: " . $conn->error;
                    ?>
                </span>
            </div>
        </div>
    </div>
   
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Clients</span>
                <span class="info-box-number text-right">
                    <?php 
                        $result = $conn->query("SELECT * FROM `users`");
                        echo $result ? $result->num_rows : "Error: " . $conn->error;
                    ?>
                </span>
            </div>
        </div>
    </div>
    
</div>
