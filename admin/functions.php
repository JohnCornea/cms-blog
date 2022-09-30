<?php

function redirect($location) {
    header("Location:" . $location);
    exit;
}

function ifItIsMethod($method=null){

    if($_SERVER['REQUEST_METHOD'] == strtoupper($method)){
        return true;
    }
    return false;
}

function isLoggedIn(){

    if(isset($_SESSION['user_role'])){
        return true;
    }
    return false;
}

function checkIfUserIsLoggedInAndRedirect($redirectLocation=null){

    if(isLoggedIn()){
        redirect($redirectLocation);
    }
}

// implementation for safety against the MYSQL Injections
function escape($string) {
    global $connection;

    return mysqli_real_escape_string($connection, trim($string));
}

function users_online()
{
    if (isset($_GET['onlineusers'])) {

        global $connection;

        if (!$connection) {
            session_start();
            include("../includes/db.php");

            $session = session_id();
            $time = time();
            $time_out_in_seconds = 05;
            $time_out = $time - $time_out_in_seconds;

            $query = "SELECT * FROM users_online WHERE session = '$session'";
            $send_query = mysqli_query($connection, $query);
            $count = mysqli_num_rows($send_query);

            if ($count == NULL) {
                mysqli_query($connection, "INSERT INTO users_online(session, time) VALUES ('$session', '$time')");
            } else {
                mysqli_query($connection, "UPDATE users_online SET time = '$time' WHERE session = '$session'");
            }
            $users_online_query = mysqli_query($connection, "SELECT * FROM users_online WHERE time > '$time_out'");
            echo $count_user = mysqli_num_rows($users_online_query);
        }
    } // get request isset
}
users_online();

// we need $result as parameter
function confirmQuery($result)
{
    global $connection;
    if (!$result) {
        die("QUERY FAILED." . mysqli_error($connection));
    }
}

// from the future - (refresh the page after submitting a comment)
//function redirect($location) {
//    return header(header: "Location:" . $location);
//}

function insert_categories()
{
    global $connection;

    if (isset($_POST['submit'])) {
        $cat_title = $_POST['cat_title'];

        if ($cat_title == "" || empty($cat_title)) {
            echo "This field should not be empty";
        } else {
            $query = "INSERT INTO categories(cat_title)";
            $query .= "VALUE('{$cat_title}')";

            $create_category_query = mysqli_query($connection, $query);
            if (!$create_category_query) {
                die('QUERY FAILED' . mysqli_error($connection));
            }
        }
    }
}

function update_categories()
{
    global $connection;

    if (isset($_GET['edit'])) {
        $cat_id = $_GET['edit'];

        include "includes/update_categories.php";
    }
}

function findAllCategories()
{
    global $connection;

    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);

    while ($row = mysqli_fetch_assoc($select_categories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        echo "<tr>";
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";
        // one line, deleting the article based on Id
        echo "<td><a class='btn btn-danger' href='categories.php?delete={$cat_id}'>Delete</a></td>";
        echo "<td><a class='btn btn-info' href='categories.php?edit={$cat_id}'>Update</a></td>";
        echo "</tr>";
    }
}

function deleteCategories()
{
    global $connection;

    if (isset($_GET['delete'])) {
        $the_cat_id = $_GET['delete'];
        $query = "DELETE FROM categories WHERE cat_id={$the_cat_id}";
        $delete_query = mysqli_query($connection, $query);
        // when deleting is done, refresh the page
        header("Location: categories.php");
    }
}
/*** refactoring the code for getting the posts, users, comments, etc. ***/
function recordCount($table) {
    global $connection;

    $query = "SELECT * FROM " . $table;
    $select_all_posts = mysqli_query($connection, $query);

    $result = mysqli_num_rows($select_all_posts);

    confirmQuery($result);

    return $result;
}
/*** refactoring checking the status of the posts implementation ***/
function checkStatus($table, $column, $status) {
    global $connection;

    $query = "SELECT * FROM $table WHERE $column = '$status' ";
    $result = mysqli_query($connection, $query);

    return mysqli_num_rows($result);
}

function checkUserRole($table, $column, $role) {
    global $connection;

    $query = "SELECT * FROM $table WHERE $column = '$role'";
    $select_all_subscribers = mysqli_query($connection, $query);

    return mysqli_num_rows($select_all_subscribers);
}

function is_admin($username = '') {
    global $connection;

    $query = "SELECT user_role FROM users WHERE user_name = '$username'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    $row = mysqli_fetch_array($result);

    if ($row['user_role'] === 'admin') {
        return true;
    } else {
        return false;
    }
}
/** check if username already exists */
function username_exists($username) {
    global $connection;

    $query = "SELECT user_name FROM users WHERE user_name = '$username'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    if (mysqli_num_rows($result) > 0) {
        return true;
    } else
        return false;
}
/** check if email already exists */
function email_exists($email) {
    global $connection;

    $query = "SELECT user_email FROM users WHERE user_email = '$email' ";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    if (mysqli_num_rows($result) > 0) {
        return true;
    } else
        return false;
}

function register_user($username, $email, $password) {
    global $connection;

        $username = mysqli_real_escape_string($connection, $username);
        $email = mysqli_real_escape_string($connection, $email);
        $password = mysqli_real_escape_string($connection, $password);

        // encryption update implementation, better and safer
        $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

        $query = "INSERT INTO users (user_name, user_email, user_password, user_role) ";
        $query .= "VALUES('{$username}','{$email}','{$password}', 'subscriber' )";
        $register_user_query = mysqli_query($connection, $query);
        confirmQuery($register_user_query);
}

function login_user($username, $password) {
    global $connection;

    if (session_status() === PHP_SESSION_NONE) session_start();

    $username = trim($username);
    $password = trim($password);

    // not sure if I need this
    $username = mysqli_real_escape_string($connection, $username);
    $password = mysqli_real_escape_string($connection, $password);

    $query = "SELECT * FROM users WHERE user_name = '{$username}' ";
    $select_user_query = mysqli_query($connection, $query);
    if (!$select_user_query) {
        die("QUERY FAILED". mysqli_error($connection));
    }

    while ($row = mysqli_fetch_array($select_user_query)) {
        $db_user_id = $row['user_id'];
        $db_user_name = $row['user_name'];
        $db_user_password = $row['user_password'];
        $db_user_firstname = $row['user_firstname'];
        $db_user_lastname = $row['user_lastname'];
        $db_user_role = $row['user_role'];

        if (password_verify($password, $db_user_password)) {
            $_SESSION['user_name'] =  $db_user_name;
            $_SESSION['firstname'] =  $db_user_firstname;
            $_SESSION['lastname'] =  $db_user_lastname;
            $_SESSION['user_role'] =  $db_user_role;

            header("Location: ../admin");

        } else {
            return false;
        }
    }
    return true;
}
