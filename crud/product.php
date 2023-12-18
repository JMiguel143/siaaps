<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    fieldset {
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #ccc;
        max-width: 600px;
        margin: 0 auto;
        background-color: #fff;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        font-size: 20px;
    }

    input,
    select {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        margin-bottom: 10px;
    }

    button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 10px;
    }

    .cancel {
        background-color: #ccc;
        color: #000;
    }

    table {
        width: 80%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #fff;
        margin: 0 auto;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #4CAF50;
        color: white;
    }

    a.button {
        display: inline-block;
        padding: 10px 20px;
        margin: 20px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    a.button:hover {
        background-color: #45a049;
    }

    a.delete {
        color: red;
    }

    a.edit {
        color: blue;
    }

    table img {
        width: 100px;
        height: auto;
        display: block;
        margin: 0 auto;
    }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
   
</head>
<body>
<?php
session_start();
include('connection_db.php');

$product_name = "";
$price = "";
$product_code = "";

// Fetch data for editing
if (isset($_GET['edit'])) {
    $edit_product_code = $_GET['product_code'];
    $sql_fetch_edit = "SELECT * FROM `products` WHERE `product_code`='$edit_product_code'";
    $result_fetch_edit = $conn->query($sql_fetch_edit);

    if ($result_fetch_edit->num_rows > 0) {
        $row_edit = $result_fetch_edit->fetch_assoc();
        $product_name = $row_edit['product_name'];
        $price = $row_edit['price'];
        $product_code = $row_edit['product_code'];
    }
}

// Add a new product
if (isset($_POST['addProduct'])) {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $product_code = $_POST['product_code'];

    $imageFileName = $_FILES['image']['name'];
    $imageTempName = $_FILES['image']['tmp_name'];
    $targetDirectory = "profile/";
    $targetFilePath = $targetDirectory . basename($imageFileName);

    $sql_add = "INSERT INTO `products` (`product_name`, `price`, `product_code`, `profile`) VALUES ('$product_name', '$price', '$product_code', '$targetFilePath')";
    $result_add = $conn->query($sql_add);

    if ($result_add) {
        move_uploaded_file($imageTempName, $targetFilePath);

        header("Location: product.php");
        exit;
    } else {
        echo "Error adding product: " . $conn->error;
    }
}

if (isset($_POST['editProduct'])) {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $product_code = $_POST['product_code'];


    $sql_update = "UPDATE `products` SET `product_name`='$product_name', `price`='$price' WHERE `product_code`='$product_code'";
    $result_update = $conn->query($sql_update);

    if ($result_update) {
        header("Location: product.php");
        exit;
    } else {
        echo "Error updating product: " . $conn->error;
    }
}

// Delete a product
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $delete_product_code = $_GET['product_code'];
    $sql_delete = "DELETE FROM `products` WHERE `product_code`='$delete_product_code'";
    $result_delete = $conn->query($sql_delete);
    header("Location: product.php");
    exit;
}

// Fetch all products
$sql_view = "SELECT * FROM products";
$result_view = $conn->query($sql_view);
?>

<fieldset>
    <form method="post" action="product.php" enctype="multipart/form-data">
        <label><?php echo isset($_GET['edit']) ? 'Edit Product' : 'Add Product'; ?></label>
        <p>Product Image<input type="file" name="image" accept="image/png, image/jpeg" <?php echo isset($_GET['edit']) ? '' : 'required'; ?>></p>
        
        <p>Product Name <input name="product_name" value="<?php echo htmlspecialchars($product_name); ?>" required /></p>
        <p>Price <input name="price" type="number" value="<?php echo htmlspecialchars($price); ?>" required /></p>
        <p>Product Code <input type="number" name="product_code" value="<?php echo htmlspecialchars($product_code); ?>" <?php echo isset($_GET['edit']) ? 'readonly' : 'required'; ?> /></p>

        <?php if (isset($_GET['edit'])) : ?>
            <input type="hidden" name="editProduct" value="1">
            <button type="submit">Update</button>
            <a style ="color: blue;" class="cancel" href="product.php">Cancel</a>
        <?php else : ?>
            <input type="hidden" name="addProduct" value="1">
            <button type="submit">Save</button>
        <?php endif; ?>

        <a class="button" href="http://localhost/sia_appdev-main/crudes/dashboard.php">Home</a>
    </form>
</fieldset>


<center>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Product Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_view->num_rows > 0) {
                while ($row_view = $result_view->fetch_assoc()) {
                    ?>  
                    <tr>
                        <td><img width="100" src="<?php echo $row_view['profile']; ?>"></td>
                        <td><?php echo $row_view['product_name']; ?></td>
                        <td><?php echo $row_view['price']; ?></td>
                        <td><?php echo $row_view['product_code']; ?></td>
                        <td>
                            <a class="delete" href="?action=delete&product_code=<?= $row_view['product_code'] ?>">DELETE</a>
                            <a class="edit" href="?edit=1&product_code=<?= $row_view['product_code'] ?>">EDIT</a>
                            <a class="view" style="color:green;" href="http://localhost/sia_appdev-main/crudes/viewProduct.php?product_code=<?= $row_view['product_code'] ?>">VIEW</a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo '<tr><td colspan="5">No products found</td></tr>';
            }
            ?>
        </tbody> 
    </table>
</center>



</body>
</html>

<?php
$conn->close();
?>