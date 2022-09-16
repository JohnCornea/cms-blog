<!-- DB Connection -->
<?php include "includes/db.php"; ?>
<!-- Header -->
<?php include "includes/header.php"; ?>

<!-- Navigation -->
<?php include "includes/navigation.php"; ?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php
            // we want to catch the category value with the category key
            if (isset($_GET['category'])) {
                // i removed the escape() for the GET category bcuz of error, must recheck why the error triggered
                $post_category_id = $_GET['category'];

                if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                    $query = "SELECT * FROM posts WHERE post_category_id = $post_category_id ";

                } else {
                    $query = "SELECT * FROM posts WHERE post_category_id = $post_category_id AND post_status = 'published' ";
                }

                // AND sql condition added for 'no categories' implementation
                $select_all_posts_query = mysqli_query($connection, $query);

                //if row are less than 1 it means no category available
                if (mysqli_num_rows($select_all_posts_query) < 1) {
                    echo "<h1 class='text-center'>No categories available</h1>";
                } else {

                    while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
                        $post_id = $row['post_id'];
                        $post_title = $row['post_title'];
                        $post_author = $row['post_author'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        // display only a specific length of the post text
                        $post_content = substr($row['post_content'], 0, 250);
                        ?>

                        <h1 class="page-header">
                            Page Heading
                            <small>Secondary Text</small>
                        </h1>

                        <!-- First Blog Post -->
                        <h2>
                            <!--When click on the link of the category in the cms, we will send a parameter in order to GET all the articles from a specific category. Code below-->
                            <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title ?></a>
                        </h2>
                        <p class="lead">
                            by <a href="index.php"><?php echo $post_author ?></a>
                        </p>
                        <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
                        <hr>
                        <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                        <hr>
                        <p><?php echo $post_content ?></p>
                        <a class="btn btn-primary" href="#">Read More
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>
                        <hr>
                    <?php }
                }
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

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

</body>

</html>

<?php include "includes/footer.php"; ?>
