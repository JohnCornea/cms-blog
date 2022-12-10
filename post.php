<!-- DB Connection -->
<?php include "includes/db.php"; ?>
<!-- Header -->
<?php include "includes/header.php"; ?>

<!-- Navigation -->
<?php include "includes/navigation.php"; ?>

<?php

if (isset($_POST['liked'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    // #1 -> FETCHING THE RIGHT POST
    $query = "SELECT * FROM posts WHERE post_id=$post_id";
    $postResult = mysqli_query($connection, $query);
    $post = mysqli_fetch_array($postResult);
    $likes = $post['likes'];

    // #2 -> UPDATE - INCREMENTING WITH LIKES
    mysqli_query($connection, "UPDATE posts SET likes=$likes+1 WHERE post_id=$post_id");

    // #3 -> CREATE LIKES FOR POST
    mysqli_query($connection, "INSERT INTO likes(user_id, post_id) VALUES($user_id, $post_id)");
    exit();
}

if (isset($_POST['unliked'])) {
//    echo "UNLIKED!";
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    // #1 -> FETCHING THE RIGHT POST
    $query = "SELECT * FROM posts WHERE post_id=$post_id";
    $postResult = mysqli_query($connection, $query);
    $post = mysqli_fetch_array($postResult);
    $likes = $post['likes'];

    // #2 DELETE LIKES
    mysqli_query($connection, "DELETE FROM likes WHERE post_id=$post_id AND user_id=$user_id");

    // #3 -> UPDATE - DECREMENTING WITH LIKES
    mysqli_query($connection, "UPDATE posts SET likes=$likes-1 WHERE post_id=$post_id");
    exit();

}

?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php

            // we catch the p_id for the filter of the categories functionality
            if (isset($_GET['p_id'])) {
                $the_post_id = $_GET['p_id'];

                $view_query = "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = $the_post_id";
                $send_query = mysqli_query($connection, $view_query);

                if (!$send_query) {
                    die("query failed");
                }

                if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                    $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";

                } else {
                    $query = "SELECT * FROM posts WHERE post_id = $the_post_id AND post_status = 'published' ";
                }

                $select_all_posts_query = mysqli_query($connection, $query);

                if (mysqli_num_rows($select_all_posts_query) < 1) {
                    echo "<h1 class='text-center'>No categories available</h1>";
                } else {

                    while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
                        $post_title = $row['post_title'];
                        $post_author = $row['post_user'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];
                        ?>

                        <h1 class="page-header">
                            Posts
                        </h1>

                        <!-- First Blog Post -->
                        <h2>
                            <a href="#"><?php echo $post_title ?></a>
                        </h2>
                        <p class="lead">
                            by <a href="index.php"><?php echo $post_author ?></a>
                        </p>
                        <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
                        <hr>
                        <img class="img-responsive" src="images/<?php echo imagePlaceholder($post_image); ?>" alt="">
                        <hr>
                        <p><?php echo $post_content ?></p>

                        <?php
                        // Must convert few things to STMT, lesson #320. Comeback
//                        mysqli_stmt_free_result($stmt);

                        ?>

                        <a class="btn btn-primary" href="#">Read More
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>
                        <hr>

                        <?php

                            if (isLoggedIn()) { ?>
                                <!-- Adding the div for #no of Likes per posts -->
                                <div class="row">
                                    <p class="pull-right"><a class="<?php echo userLikedThisPost($the_post_id) ? 'unlike' : 'like'; ?>"
                                                             href="javascript:window.location.href=window.location.href"><span
                                                             class="glyphicon glyphicon-thumbs-up"
                                                             data-toggle="tooltip"
                                                             data-placement="top"
                                                             title="<?php echo userLikedThisPost($the_post_id) ? ' I liked this before' : ' Want to like it?'; ?>"
                                            ></span>
                                            <?php echo userLikedThisPost($the_post_id) ? ' Unlike' : ' Like'; ?></a></p>
                                </div>
                            <?php } else { ?>
                                <div class="row">
                                    <p class="pull-right login-to-post">You need to <a href="/login.php">Login</a> to like</p>
                                </div>
                            <?php }
                        ?>


                        <div class="row">
                            <p class="pull-right likes">Like: <?php getPostLikes($the_post_id); ?></p>
                        </div>
                        <div class="clearfix"></div>
                    <?php }


                    ?>

                    <!-- Blog Comments -->
                    <?php

                    if (isset($_POST['create_comment'])) {

                        // if isset, we need to get the p_id from the URL
                        $the_post_id = escape($_GET['p_id']);

                        // when we click on the submit button, we will get the data from the inputs from below + the p_id from GET
                        $comment_author = escape($_POST['comment_author']);
                        $comment_email = escape($_POST['comment_email']);
                        $comment_content = escape($_POST['comment_content']);

                        if (!empty($comment_content) && !empty($comment_email) && !empty($comment_content)) {
                            $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) ";

                            $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'unapproved', now())";

                            // creating the query
                            $create_comment_query = mysqli_query($connection, $query);
                            if (!$create_comment_query) {
                                die('QUERY FAILED' . mysqli_error($connection));
                            }

//                    $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
//                    $query .= "WHERE post_id = $the_post_id ";
//                    $update_comment_count = mysqli_query($connection, $query);
                        } else {
                            echo "<script>alert('Fields cannot be empty!')</script>";
                        }
                        // might need this for future implementations (refresh the page after submitting a comment)
//                redirect(location: "/cms/post.php?p_id=$the_post_id");

                    }
                }
                ?>

                <!-- Comments Form -->
                <div class="well">
                    <h4>Leave a Comment:</h4>
                    <form action="" method="post" role="form">
                        <div class="form-group">
                            <label for="author">Author</label>
                            <input type="text" class="form-control" name="comment_author">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="comment_email">
                        </div>
                        <div class="form-group">
                            <label for="comment">Your Comment</label>
                            <textarea name="comment_content" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" name="create_comment" class="btn btn-primary">Submit your comment</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->
                <?php
                $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ";
                $query .= "AND comment_status = 'approved' ";
                $query .= "ORDER BY comment_id DESC ";
                $select_comment_query = mysqli_query($connection, $query);
                if (!$select_comment_query) {
                    die('Query Failed' . mysqli_error($connection));
                }
                while ($row = mysqli_fetch_array($select_comment_query)) {
                    $comment_date = $row['comment_date'];
                    $comment_content = $row['comment_content'];
                    $comment_author = $row['comment_author'];
                    ?>

                    <!-- Comment -->
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" src="http://placehold.it/64x64" alt="">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading"><?php echo $comment_author; ?>
                                <small><?php echo $comment_date; ?></small>
                            </h4>
                            <?php echo $comment_content; ?>
                        </div>
                    </div>

                <?php }
            } else {
                header("Location: index.php");
            } ?>

        </div>
        <!-- Blog Sidebar Widgets Column -->
        <?php include "includes/sidebar.php"; ?>

    </div>
    <!-- /.row -->
    <hr>
</div>
<!-- /.container -->

<?php include "includes/footer.php"; ?>

<script>
    jQuery(document).ready(function () {

        // Making sure the tooltip for LIKING/UNLIKING works, by activating it with jQuery
        jQuery("[data-toggle='tooltip']").tooltip();
        var post_id = <?php echo $the_post_id; ?>;
        var user_id = <?php echo loggedInUserId(); ?>;

        // LIKING
        jQuery('.like').click(function (e) {
            // e.preventDefault();
            console.log('IT WORKSSSSSSSSSSSSS');
            // ajax to send requests to the server
            jQuery.ajax({
                url: "/post.php?p_id=<?php echo $the_post_id; ?>",
                type: 'post',
                data: {
                    'liked': 1,
                    'post_id': post_id,
                    'user_id': user_id
                }
            });
        });

        // UNLIKING
        jQuery('.unlike').click(function (e) {
            // e.preventDefault();
            console.log('UNLIKED BUTTON WORKS');
            // ajax to send requests to the server
            jQuery.ajax({
                url: "/post.php?p_id=<?php echo $the_post_id; ?>",
                type: 'post',
                data: {
                    'unliked': 1,
                    'post_id': post_id,
                    'user_id': user_id
                }
            });
        });
    });
</script>