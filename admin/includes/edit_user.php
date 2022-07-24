<?php
if (isset($_GET['edit_user'])) {
    $the_user_id = $_GET['edit_user'];
    $query = "SELECT * FROM users  WHERE user_id = $the_user_id";
    $select_users_query = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($select_users_query)) {
        $user_id = $row['user_id'];
        $user_name = $row['user_name'];
        $user_password = $row['user_password'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_image = $row['user_image'];
        $user_role = $row['user_role'];
    }

    ?>

    <?php


    if (isset($_POST['edit_user'])) {
        $user_firstname = escape($_POST['user_firstname']);
        $user_lastname = escape($_POST['user_lastname']);
        $user_role = escape($_POST['user_role']);
        $user_name = escape($_POST['user_name']);
        $user_email = escape($_POST['user_email']);
        $user_password = escape($_POST['user_password']);
        $post_date = date('d-m-y');

        if (!empty($user_password)) {
            $query_password = "SELECT user_password FROM users WHERE user_id = $the_user_id";
            $get_user_query = mysqli_query($connection, $query_password);
            confirmQuery($get_user_query);

            $row = mysqli_fetch_array($get_user_query);
            $db_user_password = $row['user_password'];

            if ($db_user_password != $user_password) {
                $hashed_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 12));
            }
            $query = "UPDATE users SET ";
            $query .= "user_firstname = '{$user_firstname}', ";
            $query .= "user_lastname = '{$user_lastname}', ";
            $query .= "user_role = '{$user_role}', ";
            $query .= "user_name = '{$user_name}', ";
            $query .= "user_email = '{$user_email}', ";
            $query .= "user_password = '{$hashed_password}' ";
            $query .= "WHERE user_id = {$the_user_id} ";

//    if (empty($user_image)) {
//
//        $query_for_image = "SELECT * FROM users WHERE user_id = $the_user_id ";
//        $select_image = mysqli_query($connection, $query_for_image);
//
//        while ($row = mysqli_fetch_array($select_image)) {
//            $user_image = $row['user_image'];
//        }
//    }
            $edit_user_query = mysqli_query($connection, $query);
            confirmQuery($edit_user_query);
        }
        echo "User Updated" . " <a href='users.php'>View Users?</a>";
    }
} else{
    header("Location: index.php");
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">First Name</label>
        <input type="text" value="<?php echo $user_firstname; ?>" class="form-control" name="user_firstname">
    </div>
    <div class="form-group">
        <label for="post_status">Last Name</label>
        <input type="text" value="<?php echo $user_lastname; ?>" class="form-control" name="user_lastname">
    </div>

    <div class="form-group">
        <select name="user_role" id="">
            <option value="<?php echo $user_role; ?>"><?php echo $user_role; ?></option>

            <?php
            if ($user_role == 'admin') {
                echo "<option value='subscriber'>subscriber</option>";
            } else {
                echo "<option value='admin'>admin</option>";
            }
            ?>
        </select>
    </div>
<!--    <div class="form-group">-->
<!--        <img width=100 src="../images/profiles/--><?php //echo $user_image; ?><!--" alt="">-->
<!--        <input type="file" name="image">-->
<!--    </div>-->
    <div class="form-group">
        <label for="post_tags">Username</label>
        <input type="text" value="<?php echo $user_name; ?>" class="form-control" name="user_name">
    </div>
    <div class="form-group">
        <label for="post_content">Password</label>
        <input autocomplete="off" type="password" class="form-control" name="user_password">
    </div>
    <div class="form-group">
        <label for="post_content">Email</label>
        <input type="email" value="<?php echo $user_email; ?>" class="form-control" name="user_email">
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="edit_user" value="Update User">
    </div>
</form>