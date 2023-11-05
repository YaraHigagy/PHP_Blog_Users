<?php
    $error_fields = array();
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
            //Connect to DB
            $conn = mysqli_connect("localhost", "root", "", "blog");
            if(!$conn) {
                echo mysqli_connect_error();
                exit;
            }

            //Escape any special characters to avoid SQL Injection
            $name = mysqli_escape_string($conn, $_POST['name']);
            $email = mysqli_escape_string($conn, $_POST['email']);
            $password = sha1($_POST['password']);
            $admin = (isset($_POST['admin']))? 1 : 0;
            $uploads_dir = $_SERVER['DOCUMENT_ROOT'].'/uploads';
            $avatar = '';
            if($_FILES["avatar"]['error'] == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["avatar"]["tmp_name"];
                $avatar = basename($_FILES["avatar"]["name"]);
                move_uploaded_file($tmp_name, "$uploads_dir/$name.$avatar");
            } else {
                echo "File cant be uploaded";
                exit;
            }
var_dump($_SERVER['DOCUMENT_ROOT']);
            //Insert the data
            $query = "INSERT INTO `users` (`name`, `email`, `avatar`, `password`, `admin`)
                    VALUES ('".$name."', '".$email."', '".$avatar."', '".$password."', '".$admin."')";
            if(mysqli_query($conn, $query)) {
                header("Location: list.php");
                exit;
            } else {
                // echo $query;
                echo mysqli_error($conn);
            }
            
            //Close the connection
            mysqli_close($conn);
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
        <form class="m-4" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                <small><?php if(in_array("name", $error_fields)) echo "* Please enter your name"; ?></small>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                <small><?php if(in_array("email", $error_fields)) echo "* Please enter a valid email"; ?></small>
                <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                <small><?php if(in_array("password", $error_fields)) echo "* Please enter a password not less than 6 characters"; ?></small>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="admin" id="admin"
                        <?= (isset($_POST['admin']))? 'checked' : '' ?>>
                <label class="form-check-label" for="admin">Admin</label>
            </div>
            <div class="form-group">
                <label for="avatar">Avatar</label>
                <input type="file" name="avatar" class="form-control" id="avatar">
            </div>
            <br>
            <button class="btn btn-primary mt-3" type="submit" name="submit">Add User</button>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>