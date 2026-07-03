<?php
    require_once __DIR__ . "/../../includes/auth.php";
    require_login();
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = intval($_POST['id']);
    $image = $_POST['image'];

    $path = $image;

    if (file_exists($path)) {
        unlink($path);
    }
    $conn->query("DELETE FROM products WHERE p_id = $id");

        echo "Deleted Successfully";
}

?>