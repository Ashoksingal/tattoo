
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

@include 'config.php';

if (isset($_POST['add_product'])) {

   $product_name = $_POST['product_name'];
   $product_subname = $_POST['product_subname'];
   $product_image = $_FILES['product_image']['name'];
   $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
   $product_image_folder = 'uploaded_img/' . $product_image;

   if (empty($product_name) || empty($product_subname) || empty($product_image)) {
      $message[] = 'please fill out all';
   } else {
      $insert = "INSERT INTO products(name, subname, image) VALUES('$product_name', '$product_subname', '$product_image')";
      $upload = mysqli_query($conn, $insert);
      if ($upload) {
         move_uploaded_file($product_image_tmp_name, $product_image_folder);
         // Redirect the user to a different page after the product is added successfully
         header('location:success_page.php');
         exit;
      } else {
         $message[] = 'could not add the product';
      }
   }

}
;

if (isset($_GET['delete'])) {
   $id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM products WHERE id = $id");
   // Redirect the user to a different page after the product is deleted successfully
   header('location:admin_page.php');
   exit;
}
;

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

                  <h3>add a new product</h3>
                  <div class="form">
                     <input type="text" placeholder="enter product name" name="product_name" class="form-control">
                     <input type="text" placeholder="enter product subname" name="product_subname" class="form-control">
                     <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image"
                        class="form-control">
                     <input type="submit" class="btn btn-primary" name="add_product" value="add product">
                     <a href="welcome.php" class="btn btn-danger w-100">back</a>
                  </div>
               </form>

            </div>
         </div>
         <?php

         $select = mysqli_query($conn, "SELECT * FROM products");

         ?>
         <div class="col-md-9 col-sm-12 col-12">
            <div class="product-display">
               <form method="GET" action="">
                  <div class="d-flex"><input type="text" name="search" placeholder="Search by name"
                        class="form-control">
                     <button type="submit" class="btn-primary btn">Search</button>
                  </div>
               </form>
               <table class="product-display-table">
                  <thead>
                     <tr>
                        <th>product image</th>
                        <th>product name</th>
                        <th>product subname</th>
                        <th>action</th>
                     </tr>
                  </thead>
                  <?php
                  $limit = 6;
                  $page = isset($_GET['page']) ? $_GET['page'] : 1; // get the current page number
                  $offset = ($page - 1) * $limit; // calculate the offset based on the page number and limit
                  
                  $search = isset($_GET['search']) ? $_GET['search'] : ''; // get the search query
                  $count_query = "SELECT COUNT(*) AS count FROM products WHERE name LIKE '%$search%'";
                  $select_query = "SELECT * FROM products WHERE name LIKE '%$search%' LIMIT $offset, $limit";

                  $count_result = mysqli_query($conn, $count_query);
                  $count_row = mysqli_fetch_assoc($count_result);
                  $count = $count_row['count'];

                  if ($count > 0) {
                     $select_result = mysqli_query($conn, $select_query);
                     $rows = mysqli_fetch_all($select_result, MYSQLI_ASSOC);
                     foreach ($rows as $row) {
                        ?>
                        <tr>
                           <td><img src="uploaded_img/<?php echo $row['image']; ?>" height="100" alt=""></td>
                           <td>
                              <?php echo $row['name']; ?>
                           </td>
                           <td>
                              <?php echo $row['subname']; ?>
                           </td>
                           <td>
                              <a href="admin_update.php?edit=<?php echo $row['id']; ?>" class="btn btn-success"><i
                                    class="fas fa-edit"></i> edit</a>
                              <a href="admin_page.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger"><i
                                    class="fas fa-trash"></i> delete</a>
                           </td>
                        </tr>
                        <?php
                     }
                  } else {
                     echo "<tr><td colspan='4'>No data</td></tr>"; // display "No data" message
                  }

                  if ($count > $limit) {
                     $total_pages = ceil($count / $limit); // calculate the total number of pages
                     $prev_page = $page > 1 ? $page - 1 : 1; // calculate the previous page number
                     $next_page = $page < $total_pages ? $page + 1 : $total_pages; // calculate the next page number
                  
                     echo '<tr><td colspan="4">';
                     echo '<ul class="pagination">';
                     echo '<li><a href="?page=' . $prev_page . '&search=' . $search . '">&laquo;</a></li>';
                     for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<li' . ($page == $i ? ' class="active"' : '') . '><a href="?page=' . $i . '&search=' . $search . '">' . $i . '</a></li>';
                     }
                     echo '<li><a href="?page=' . $next_page . '&search=' . $search . '">&raquo;</a></li>';
                     echo '</ul>';
                     echo '</td></tr>';
                  }
                  ?>
               </table>
             
            </div>
         </div>
      </div>
   </div>

</body>

</html>