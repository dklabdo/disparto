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

if ($_settings->chk_flashdata('success')): ?>
<script>
    alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
</script>
<?php endif; ?>

<style>
    .img-avatar {
        width: 45px;
        height: 45px;
        object-fit: cover;
        object-position: center center;
        border-radius: 100%;
    }
</style>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">List of Candidat</h3>
        <div class="card-tools">
           <!-- <a href="?page=userlist/manage_user" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Create New</a>-->
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>password</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT * FROM `users` WHERE id != '1' ORDER BY username ASC");
                        while ($row = $qry->fetch_assoc()):
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['username']; ?></p></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['password']; ?></td>
                            <td align="center">
                                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item" href="?page=userlist/manage_user&id=<?php echo $row['id']; ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
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
        $('.delete_data').click(function(){
            _conf("Are you sure to delete this User permanently?", "delete_user", [$(this).attr('data-id')])
        })
        $('.table td, .table th').addClass('py-1 px-2 align-middle')
        $('.table').dataTable();
    })

    function delete_user(id){
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Users.php?f=delete",
            method: "POST",
            data: { id: id },
            dataType: "json",
            error: function(err){
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function(resp){
                if (typeof resp == 'object' && resp.status == 'success'){
                    alert_toast("User successfully deleted.", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        })
    }

    function _conf(message, callback, params) {
        if (confirm(message)) {
            window[callback](...params);
        }
    }

    function start_loader() {
        // Implémentez ici le code pour afficher un loader (spinner)
        console.log("Loading...");
    }

    function end_loader() {
        // Implémentez ici le code pour masquer le loader (spinner)
        console.log("Loading finished.");
    }

    function alert_toast(message, type) {
        // Implémentez ici le code pour afficher un message toast
        console.log(type + ": " + message);
    }
</script>
