<?php
$conn = mysqli_connect('localhost', 'root', '', 'tattoo_db');
?>

<?php
$query = "SELECT * FROM price";
?>

<form method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="record_id" value="<?php echo isset($_POST['record_id']) ? $_POST['record_id'] : ''; ?>">

    <label for="service_name">Service Name:</label>
    <input type="text" name="service_name" id="service_name" value="<?php echo isset($_POST['record_id']) ? $_POST['service_name'] : ''; ?>">

    <label for="price_value">Price Value:</label>
    <input type="number" name="price_value" id="price_value" value="<?php echo isset($_POST['record_id']) ? $_POST['price_value'] : ''; ?>">
    <input type="file" accept="image/png, image/jpeg, image/jpg" name="image" class="form-control">

    <button type="submit"><?php echo isset($_POST['record_id']) ? 'Update' : 'Submit'; ?></button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $record_id = $_POST['record_id'];
    $service_name = $_POST['service_name'];
    $price_value = $_POST['price_value'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = realpath('uploaded_images') . '/' . $image;

        if (!empty($record_id)) {
            // Update existing record
            $update_query = "UPDATE price SET `service-name`='$service_name', `price-value`='$price_value', `image`='$image' WHERE id='$record_id'";

            if (mysqli_query($conn, $update_query)) {
                echo "Data updated successfully";
            } else {
                echo "Error updating data: " . mysqli_error($conn);
            }
        } else {
            // Insert new record
            $insert_query = "INSERT INTO price (`service-name`, `price-value`, `image`) VALUES ('$service_name', '$price_value', '$image')";

            if (mysqli_query($conn, $insert_query)) {
                move_uploaded_file($image_tmp_name, $image_folder);
                echo "Data inserted successfully";
                echo "<br>";

                // Fetch the inserted data
                $select_query = "SELECT * FROM price WHERE id = LAST_INSERT_ID()";
                $result = mysqli_query($conn, $select_query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    echo "Inserted Data:<br>";
                    echo "ID: " . $row['id'] . "<br>";
                    echo "Service Name: " . $row['service-name'] . "<br>";
                    echo "Price Value: " . $row['price-value'] . "<br>";
                    echo "Image: " . $row['image'] . "<br>";
                }
            } else {
                echo "Error inserting data: " . mysqli_error($conn);
            }
        }
    } elseif (isset($_FILES['image'])) {
        echo "Error uploading image: " . $_FILES['image']['error'];
    } else {
        echo "Image file is missing or not uploaded.";
    }
}
?>
