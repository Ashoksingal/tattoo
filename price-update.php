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

if (isset($_POST['change_price'])) {

    $service_name = $_POST['service_name'];
    $price_value = $_POST['price_value'];
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

   if (empty($service_name) || empty($price_value) || empty($image)) {
      $message[] = 'please fill out all!';
   } else {

    $update_data = "UPDATE price SET servicename='$service_name', pricevalue='$price_value', image='$image'  WHERE id = '$id'";

      $upload = mysqli_query($conn, $update_data);
    

      if ($upload) {
        move_uploaded_file($image_tmp_name, $image_folder);
        header('Location: price.php');
        exit;
     } else {
        $message[] = 'Please fill out all fields!';
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

               $select = mysqli_query($conn, "SELECT * FROM price WHERE id = '$id'");
               while ($row = mysqli_fetch_assoc($select)) {

                  ?>
                
                  <form action="" method="post" enctype="multipart/form-data">
                     <h5 class="text-center">update the price</h5>
                     <div class="form">
                     <input type="text" placeholder="enter product name" name="service_name" class="form-control">
                        <input type="text" placeholder="enter product subname" name="price_value" class="form-control">
                        <input type="file" accept="image/png, image/jpeg, image/jpg" name="image"
                           class="form-control">
                        <input type="submit" value="update price" name="change_price" class="btn btn-primary">
                        <a href="price.php" class="btn btn-danger">back!</a>
                     </div>
                  </form> 



               <?php }
                ?>



            </div>


            
         </div>


 

</body>

</html>