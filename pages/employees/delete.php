<?php
    require_once __DIR__ . "/../../includes/auth.php";
    require_login();
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = intval($_POST['id'] ?? 0);
    $image = $_POST['image'] ?? '';

    if ($id > 0) {
        // remove image file if exists and path is within uploads
        if (!empty($image) && strpos($image, 'uploads/') === 0) {
            $path = __DIR__ . '/../../' . $image;
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $stmt = $conn->prepare("DELETE FROM employees WHERE employee_id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            redirect_to('pages/employees/index.php?status=deleted');
        }
    }

    redirect_to('pages/employees/index.php?error=delete_failed');
}

?>