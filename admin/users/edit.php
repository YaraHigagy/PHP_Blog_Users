<?php
require '../models/User.php';

$error_fields = array();
$userData_arr = array();

//Select the user
$user = new User();
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$row = $user->getUser($id);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //Validation
    if(! (isset($_POST['name']) && !empty($_POST['name']))) {
        $error_fields[] = "name";
    }
    if(! (isset($_POST['email']) && filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL))) {
        $error_fields[] = "email";
    }
    if(! (isset($_POST['password']) && strlen($_POST['password']) > 5)) {
        $error_fields[] = "password";
    }

    if(!$error_fields) {
        //User data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = sha1($_POST['password']);
        $admin = (isset($_POST['admin']))? 1 : 0;

        //Update the data
        $userData_arr = ['name' => $name,
                        'email' => $email,
                        'password' => $password,
                        'admin' => $admin];

        //Update the data
        $query = $user->updateUser($userData_arr, $id);

        if($query) {
            header("Location: list.php");
            exit;
        } else {
            echo "Error: User Not Updated";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <title>Admin :: Add User</title>
    </head>
    <body>
        <form class="m-4" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Name"
                        value="<?= (isset($row['name'])) ? $row['name'] : '' ?>">
                <small><?php if(in_array("name", $error_fields)) echo "* Please enter your name"; ?></small>
            </div>
            <div class="form-group">
                <input type="hidden" name="id" id="id"
                        value="<?= (isset($row['id'])) ? $row['id'] : '' ?>"/>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email"
                        value="<?= (isset($row['email'])) ? $row['email'] : '' ?>">
                <small><?php if(in_array("email", $error_fields)) echo "* Please enter a valid email"; ?></small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                <small><?php if(in_array("password", $error_fields)) echo "* Please enter a password not less than 6 characters"; ?></small>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="admin" id="admin"
                        <?= ($row['admin'])? 'checked' : '' ?>>
                <label class="form-check-label" for="admin">Admin</label>
            </div>
            <br>
            <button class="btn btn-primary mt-3" type="submit" name="submit">Edit User</button>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>