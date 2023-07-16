<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<?php

$conn = mysqli_connect('localhost', 'root', '', 'tattoo_db');

?>
<?php


if (isset($_POST['change_price'])) {
    $service_name = $_POST['service_name'];
    $price_value = $_POST['price_value'];
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'path_to_upload_directory/' . $image;

    if (empty($service_name) || empty($price_value) || empty($image)) {
        $message[] = 'Please fill out all fields';
    } else {
        $insert = "INSERT INTO price (servicename, pricevalue, image) VALUES ('$service_name', '$price_value', '$image')";
        $upload = mysqli_query($conn, $insert);
        if ($upload) {
            move_uploaded_file($image_tmp_name, $image_folder);
            // Redirect the user to a different page after the product is added successfully
            header('location: pricechange_page.php');
            exit;
        } else {
            $message[] = 'Could not add the product';
        }
    }
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM price WHERE id = $id");
    // Redirect the user to a different page after the product is deleted successfully
    header('location:price.php');
    exit;
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin page</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">


</head>

<body>

    <?php

    if (isset($message)) {
        foreach ($message as $message) {
            echo '<span class="message">' . $message . '</span>';
        }
    }

    ?>

    <div class="container">
        <h4 class="page-title text-center mt-2">Artaddict Admin</h4>
        <div class="admin-main-row row">
            <div class="col-md-3 col-sm-12 col-12">
                <div class="admin-product-form-container">

                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">

                        <h3>add a new price</h3>
                        <div class="form">
                            <input type="text" placeholder="enter service name" name="service_name"
                                class="form-control">
                            <input type="text" placeholder="enter price" name="price_value" class="form-control">
                            <input type="file" accept="image/png, image/jpeg, image/jpg" name="image"
                                class="form-control">

                            <input type="submit" class="btn btn-primary" name="change_price" value="add price">
                            <a href="welcome.php" class="btn btn-danger">back!</a>
                        </div>
                    </form>

                </div>
            </div>
            <?php

            $select = mysqli_query($conn, "SELECT * FROM price");

            ?>
            <div class="col-md-9 col-sm-12 col-12">
                <div class="product-display">
                    <table class="product-display-table">
                        <thead>
                            <tr>
                                <th>product image</th>
                                <th>service name</th>
                                <th>price value</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <?php
                        $limit = 6;
                        $page = isset($_GET['page']) ? $_GET['page'] : 1; // get the current page number
                        $offset = ($page - 1) * $limit; // calculate the offset based on the page number and limit
                        
                        // Modify the SQL query to remove search and limit the results
                        $select_query = "SELECT * FROM price LIMIT $offset, $limit";

                        $select_result = mysqli_query($conn, $select_query);

                        // Check for query execution errors
                        if (!$select_result) {
                            echo "Error executing select query: " . mysqli_error($conn);
                            exit;
                        }

                        $rows = mysqli_fetch_all($select_result, MYSQLI_ASSOC);
                        foreach ($rows as $row) {
                            ?>
                            <tr>
                                <td><img src="uploaded_img/<?php echo $row['image']; ?>" height="100" alt=""></td>
                                <td>
                                    <?php echo $row['servicename']; ?>
                                </td>
                                <td>
                                    <?php echo $row['pricevalue']; ?>
                                </td>
                                <td>
                                    <a href="price-update.php?edit=<?php echo $row['id']; ?>" class="btn btn-success"><i
                                            class="fas fa-edit"></i> edit</a>
                                    <a href="price.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger"><i
                                            class="fas fa-trash"></i> delete</a>
                                </td>
                            </tr>
                            <?php
                        }

                        // Calculate the total number of pages
                        $total_pages_query = "SELECT COUNT(*) AS count FROM price";
                        $total_pages_result = mysqli_query($conn, $total_pages_query);

                        // Check for query execution errors
                        if (!$total_pages_result) {
                            echo "Error executing count query: " . mysqli_error($conn);
                            exit;
                        }

                        $total_pages_row = mysqli_fetch_assoc($total_pages_result);
                        $total_pages = ceil($total_pages_row['count'] / $limit);

                        if ($total_pages > 1) {
                            echo '<tr><td colspan="4">';
                            echo '<ul class="pagination">';
                            $prev_page = $page > 1 ? $page - 1 : 1; // calculate the previous page number
                            $next_page = $page < $total_pages ? $page + 1 : $total_pages; // calculate the next page number
                            echo '<li><a href="?page=' . $prev_page . '">&laquo;</a></li>';
                            for ($i = 1; $i <= $total_pages; $i++) {
                                echo '<li' . ($page == $i ? ' class="active"' : '') . '><a href="?page=' . $i . '">' . $i . '</a></li>';
                            }
                            echo '<li><a href="?page=' . $next_page . '">&raquo;</a></li>';
                            echo '</ul>';
                            echo '</td></tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>

        </div>
    </div>
    </div>

</body>

</html>