<?php

$conn = mysqli_connect('localhost', 'root', '', 'tattoo_db');

?>
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

@include 'config.php';

$id = $_GET['edit'];

if (isset($_POST['update_product'])) {

   $product_name = $_POST['product_name'];
   $product_subname = $_POST['product_subname'];
   $product_image = $_FILES['product_image']['name'];
   $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
   $product_image_folder = 'uploaded_img/' . $product_image;

   if (empty($product_name) || empty($product_subname) || empty($product_image)) {
      $message[] = 'please fill out all!';
   } else {

      $update_data = "UPDATE products SET name='$product_name', subname='$product_subname', image='$product_image'  WHERE id = '$id'";
      $upload = mysqli_query($conn, $update_data);
    

      if ($upload) {
         move_uploaded_file($product_image_tmp_name, $product_image_folder);
         header('location:admin_page.php');
      } else {
         $$message[] = 'please fill out all!';
      }

   }
}
;

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

     
            <div class="admin-product-form-container  ">

               <?php

               $select = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
               while ($row = mysqli_fetch_assoc($select)) {

                  ?>
                
                  <form action="" method="post" enctype="multipart/form-data">
                     <h5 class="text-center">update the work</h5>
                     <div class="form">
                     <input type="text" placeholder="enter product name" name="product_name" class="form-control">
                        <input type="text" placeholder="enter product subname" name="product_subname" class="form-control">
                        <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image"
                           class="form-control">
                        <input type="submit" value="update product" name="update_product" class="btn btn-primary">
                        <a href="admin_page.php" class="btn btn-danger">back!</a>
                     </div>
                  </form>



               <?php }
               ; ?>



            </div>


            
         </div>


 

</body>

</html>