<!--Admin Header-->
<?php include "includes/admin_header.php" ?>

<body>
<div id="wrapper">
    <!--Admin Navigation-->
    <?php include "includes/admin_navigation.php" ?>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Welcome to Admin
                        <small>Author</small>
                    </h1>
                    <!--form added here john-->
                    <div class="col-xs-4">
                        <!--Insert category query-->
                        <?php insert_categories(); ?>

                        <form action="" method="post">
                            <div class="form-group">
                                <label for="cat_title">Add Category</label>
                                <input class="form-control" type="text" name="cat_title">
                            </div>
                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" name="submit" value="Add Category">
                            </div>
                        </form>
                        <!--Update include query-->
                        <?php update_categories(); ?>

                    </div><!--Add category form-->

                    <div class="col-xs-4">
                        <table class="table table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Category Title</th>
                            </tr>
                            </thead>
                            <tbody>

                            <!--Display the categories from DB in a table using While Loop via query-->
                            <?php findAllCategories(); ?>

                            <!--Delete category query-->
                            <?php deleteCategories(); ?>

                            <tr>
                            </tr>
                            </tbody>
                        </table>
                    </div>

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>

    <!-- /#page-wrapper -->
</div>
    <!--Admin Footer-->
<?php include "includes/admin_footer.php" ?>

