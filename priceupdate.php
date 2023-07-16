<?php
$conn = mysqli_connect('localhost', 'root', '', 'tattoo_db');

//get product id and new price from form
$product_id = $_POST['product_id'];
$new_price = $_POST['new_price'];

//update price in database
$sql = "UPDATE product SET price = '$new_price' WHERE id = '$product_id'";
$result = mysqli_query($conn, $sql);

//check if update was successful
if($result){
  echo "Price updated successfully.";
} else {
  echo "Error updating price: " . mysqli_error($conn);
}
?>
<form method="post" action="update_price.php">
  <label for="product_id">Product ID:</label>
  <input type="text" id="product_id" name="product_id"><br>
  <label for="new_price">New Price:</label>
  <input type="text" id="new_price" name="new_price"><br>
  <input type="submit" value="Update Price">
</form>
<div><span><?php echo $row['product_id']; ?></span><span><?php echo $row['product_id']; ?></span></div>
<div><span><?php echo $row['name']; ?></span><span><?php echo $row['name']; ?></span></div>