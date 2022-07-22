<?php

 if (isset($_GET['p_id'])){
     $the_post_id = $_GET['p_id'];
 }

$query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
$select_post_by_id = mysqli_query($connection, $query);

while ($row = mysqli_fetch_assoc($select_post_by_id)) {
    $post_id = $row['post_id'];
    $post_user = $row['post_user'];
    $post_title = $row['post_title'];
    $post_category_id = $row['post_category_id'];
    $post_status = $row['post_status'];
    $post_image = $row['post_image'];
    $post_tags = $row['post_tags'];
    $post_content = $row['post_content'];
    $post_comment_count = $row['post_comment_count'];
    $post_date = $row['post_date'];
}
    // need to check if the submit button of the form is set
    if (isset($_POST['update_post'])) {

        $post_user = $_POST['post_user'];
        $post_title = $_POST['post_title'];
        $post_category_id = $_POST['post_category'];
        $post_status = $_POST['post_status'];

        $post_image = $_FILES['image']['name'];
        $post_image_temp = $_FILES['image']['tmp_name'];

        $post_content = $_POST['post_content'];
        $post_tags = $_POST['post_tags'];

        // moves the picture from a temp location to a permanent location
        move_uploaded_file($post_image_temp, "../images/$post_image");

        //make sure that the post image is not empty, if it is get it from db
        if (empty($post_image)) {
            $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
            $select_image = mysqli_query($connection, $query);

            while ($row = mysqli_fetch_array($select_image)) {
                $post_image = $row['post_image'];
            }
        }

        // concatenating the query strings
        $query = "UPDATE posts SET ";
        $query .= "post_title = '{$post_title}', ";
        $query .= "post_category_id = '{$post_category_id}', ";
        $query .= "post_date = now(), ";
        $query .= "post_user = '{$post_user}', ";
        $query .= "post_status = '{$post_status}', ";
        $query .= "post_tags = '{$post_tags}', ";
        $query .= "post_content = '{$post_content}', ";
        $query .= "post_image = '{$post_image}' ";
        $query .= "WHERE post_id = {$the_post_id}";

        // try this if String Concatenation Operator gives error above
//        $query = "UPDATE posts SET
//            post_title = '{$post_title}',
//            post_category_id = '{$post_category_id}',
//            post_date = now(),
//            post_author = '{$post_author}',
//            post_status = '{$post_status}',
//            post_tags = '{$post_tags}',
//            post_content = '{$post_content}',
//            post_image = '{$post_image}'
//            WHERE post_id = {$the_post_id}";

        $update_post = mysqli_query($connection, $query);
        confirmQuery($update_post);

        echo "<p class='bg-success'>Post Updated! <a href='../post.php?p_id={$the_post_id }'> View Post </a> or<a href='posts.php'> Edit More Posts</a></p>";
    }

?>
<!--form to edit posts-->
<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="title">Post Title</label>
        <input value="<?php echo $post_title; ?>" type="text" class="form-control" name="post_title">
    </div>

    <div class="form-group">
        <label for="categories">Categories</label>
        <select name="post_category" id="post_category">
            <?php

            $query = "SELECT * FROM categories ";
            $select_categories = mysqli_query($connection, $query);

            // we use the query to confirm this function
            confirmQuery($select_categories);

            while ($row = mysqli_fetch_assoc($select_categories)) {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];

                echo "<option value='{$cat_id}'>{$cat_title}</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="users">Users</label>
        <select name="post_user" id="post_users">

            <?php echo "<option value='{$post_user}'>{$post_user}</option>"; ?>

            <?php

            $query = "SELECT * FROM users ";
            $select_users = mysqli_query($connection, $query);

            // we use the query to confirm this function
            confirmQuery($select_users);

            while ($row = mysqli_fetch_assoc($select_users)) {
                $user_id = $row['user_id'];
                $username = $row['user_name'];

                echo "<option value='{$username}'>{$username}</option>";
            }
            ?>
        </select>
    </div>

<!--    <div class="form-group">-->
<!--        <label for="title">Post Author</label>-->
<!--        <input value="--><?php //echo $post_author; ?><!--" type="text" class="form-control" name="post_author">-->
<!--    </div>-->

    <div class="form-group">
    <select name="post_status" id="">
        <option value='<?php echo $post_status ?>'><?php echo $post_status; ?></option>
        <?php
            if($post_status == 'published') {
                echo "<option value='draft'>Draft</option>";
            } else{
                echo "<option value='published'>Publish</option>";
            }
        ?>
    </select>
    </div>

    <div class="form-group">
<!--        <label for="post_image">Post Image</label>-->
        <img width="200" src="../images/<?php echo $post_image; ?>" alt="photo">
        <input type="file" name="image">
    </div>

    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input value="<?php echo $post_tags; ?>" type="text" class="form-control" name="post_tags">
    </div>

    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea class="form-control" name="post_content" id="summernote" cols="30" rows="10"><?php echo $post_content; ?></textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_post" value="Update Post">
    </div>
</form>