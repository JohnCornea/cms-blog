<!-- DB Connection -->
<?php include "includes/db.php"; ?>
<!-- Header -->
<?php include "includes/header.php"; ?>
<!-- Navigation -->
<?php include "includes/navigation.php"; ?>
<?php require_once "./admin/functions.php"; ?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php
            $per_page = 4;

            if (isset($_GET['page'])) {
                $page = escape($_GET['page']);
            } else {
                $page = "";
            }
            if ($page == "" || $page == 1) {
                $page_1 = 0;
            } else {
                $page_1 = ($page * $per_page) - $per_page;
            }

            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                $query = "SELECT * FROM posts";

            } else {
                $query = "SELECT * FROM posts WHERE post_status = 'published' ORDER BY post_id DESC";
            }

            // variable to count the # of items per page, needed for the paginator
            $find_count = mysqli_query($connection, $query);
            $count = mysqli_num_rows($find_count);

            if ($count < 1) {
                echo "<h1 class='text-center'>No posts available</h1>";
            } else {

            $count = ceil($count / $per_page);


            $query .= " LIMIT $page_1, $per_page";
            $select_all_posts_query = mysqli_query($connection, $query);

            while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
                $post_id = $row['post_id'];
                $post_title = $row['post_title'];
                $post_author = $row['post_user'];
                $post_date = $row['post_date'];
                $post_image = $row['post_image'];
                // display only a specific length of the post text
                $post_content = substr($row['post_content'], 0, 250);
                $post_status = $row['post_status'];

                    ?>

                    <h1 class="page-header">
                        Page Heading Github Testing Git Test
                        <small>Secondary Text</small>
                    </h1>

                    <!-- First Blog Post -->
                    <!--Count of the total of pagination pages-->
<!--                    <h1>--><?php //echo $count; ?><!--</h1>-->
                    <h2>
                        <!--When the title is clicked the single post will be displayed. Code below-->
                        <a href="post/<?php echo $post_id; ?>"><?php echo $post_title ?></a>
                    </h2>
                    <p class="lead">
                        by
                        <a href="author_posts.php?author=<?php echo $post_author ?>&<?php echo $post_id; ?>"><?php echo $post_author ?></a>
                    </p>
                    <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
                    <hr>
                    <!--When the pic is clicked the single post will be displayed-->
                    <a href="post/<?php echo $post_id; ?>">
                        <img class="img-responsive" src="images/<?php echo imagePlaceholder($post_image); ?>" alt="">
                    </a>
                    <hr>
                    <p><?php echo $post_content ?></p>
                    <a class="btn btn-primary" href="post/<?php echo $post_id; ?>">Read More
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                    <hr>
                <?php } } ?>

        </div>
        <!-- Blog Sidebar Widgets Column -->
        <?php include "includes/sidebar.php"; ?>

    </div>
    <!-- /.row -->
    <hr>
    <!--Add the pager-->
    <ul class="pager">
        <?php
        for ($i = 1; $i <= $count; $i++) {

            if ($i == $page){
                //sending a get request + adding the css class in case the page is active
                echo "<li><a class='active_link' href='index.php?page={$i}'>{$i}</a></li>";
            }else {
                echo "<li><a href='index.php?page={$i}'>{$i}</a></li>";
            }

        }
        ?>
    </ul>
</div>
<!-- /.container -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

</body>

</html>

<?php include "includes/footer.php"; ?>
