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

// Définir la fonction pour afficher les messages flash
function alert_toast($message, $type) {
    echo "<script>alert('{$message}');</script>";
}

// Gérer la suppression des messages
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        alert_toast("Message deleted successfully", 'success');
    } else {
        alert_toast("An error occurred while deleting the message", 'error');
    }
}

// Charger les messages flash de succès
if (isset($_SESSION['success'])) {
    alert_toast($_SESSION['success'], 'success');
    unset($_SESSION['success']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List of Messages</title>
    <style>
        .img-avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
            object-position: center center;
            border-radius: 100%;
        }
    </style>
    <!-- Include jQuery and DataTables -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">List of Messages</h3>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <table class="table table-hover table-striped" id="messageTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 1;
                            $qry = $conn->query("SELECT * FROM contact_messages");
                            while ($row = $qry->fetch_assoc()):
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><p class="m-0 truncate-1"><?php echo $row['email']; ?></p></td>
                                <td><p class="m-0"><?php echo $row['message']; ?></p></td>
                                <td><?php echo $row['date']; ?></td>
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                        Action
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item" href="?page=message/manage_user&id=<?php echo $row['id']; ?>"><span class="fa fa-edit text-primary"></span> Answer</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#messageTable').DataTable();
            $('.delete_data').click(function(){
                var id = $(this).attr('data-id');
                if(confirm("Are you sure you want to delete this message permanently?")) {
                    $.post('', {action: 'delete', id: id}, function(response) {
                        location.reload();
                    });
                }
            });
        });

        function alert_toast(message, type) {
            // Replace with your own alert/toast implementation if needed
            alert(message);
        }

        function start_loader(){
            // Implement your own loader start logic here
            console.log('Loader started');
        }

        function end_loader(){
            // Implement your own loader end logic here
            console.log('Loader ended');
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>
