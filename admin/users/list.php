<?php
require '../models/User.php';

session_start();
if(isset($_SESSION['id'])) {
    echo '<p>Welcome '.$_SESSION['email'].' <a href="./../../logout.php">Logout</a></p>';
} else {
    header("Location: ./../../login.php");
    exit;
}

$user = new User();
$users = $user->getUsers();

//Search by the name or the email
if(isset($_GET['search'])) {
    $users = $user->searchUsers($_GET['search']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Admin :: List Users</title>
</head>
<body class="m-3">
    <h1>List users</h1>
    <form method="GET">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Enter {Name} or {Email} to search" aria-label="Recipient's username" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </div>
    </form>
    <table class="table">
    <thead>
        <tr>
        <th scope="col">Id</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Avatar</th>
        <th scope="col">Admin</th>
        <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
            //Loop on the rowset
            foreach($users as $row) {
        ?>
            <tr>
                <td><?= $row['id']?></td>
                <td><?= $row['name']?></td>
                <td><?= $row['email']?></td>
                <td><img src="../../uploads/<?= ($row['avatar'])? $row['avatar'] : 'noImg.jpg'?>" alt="Avatar" width="50px" height="50px"></td>
                <td>
                    <a href="edit.php?id=<?=$row['id']?>">Edit</a> |
                    <a href="delete.php?id=<?=$row['id']?>">Delete</a>
                </td>
            </tr>
        <?php        
            }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align: center">
                <?= count($users)?> users
            </td>
            <td colspan="3" style="text-align: center">
                <a href="add.php">
                    Add User
                </a>
            </td>
        </tr>
    </tfoot>
    </table>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>