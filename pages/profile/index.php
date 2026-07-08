<?php
require_once __DIR__ . "/../../includes/auth.php";
require_login();

// Load current user from database
$userId = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;
$currentUsername = current_user_name();
$currentRole = $_SESSION['role'] ?? $_COOKIE['role'] ?? '';
$currentPhoto = '';
$hasPhotoColumn = false;
$hasRoleColumn = false;

if ($userId) {
    $roleColRes = $conn->query("SHOW COLUMNS FROM user_s LIKE 'role'");
    $hasRoleColumn = $roleColRes && $roleColRes->num_rows > 0;
    $colRes = $conn->query("SHOW COLUMNS FROM user_s LIKE 'photo'");
    $hasPhotoColumn = $colRes && $colRes->num_rows > 0;

    if (!$hasPhotoColumn) {
        $alterResult = $conn->query("ALTER TABLE user_s ADD COLUMN photo VARCHAR(255) NULL DEFAULT NULL");
        if ($alterResult) {
            $hasPhotoColumn = true;
        }
    }

    $columns = 'username';
    if ($hasRoleColumn) {
        $columns .= ', role';
    }
    if ($hasPhotoColumn) {
        $columns .= ', photo';
    }

    $res = $conn->query("SELECT $columns FROM user_s WHERE id='" . $conn->real_escape_string($userId) . "' LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $u = $res->fetch_assoc();
        $currentUsername = $u['username'] ?? $currentUsername;
        if ($hasRoleColumn) {
            $currentRole = $u['role'] ?? $currentRole;
        }
        if ($hasPhotoColumn) {
            $currentPhoto = $u['photo'] ?? '';
        }
    }
}

// Handle profile update
$profileMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
    $newUsername = $conn->real_escape_string(trim($_POST['username'] ?? ''));
    $newRole = $conn->real_escape_string(trim($_POST['role'] ?? ''));
    $uploadPhotoPath = null;

    if ($hasPhotoColumn && !empty($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['profile_photo'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg','jpeg','png','gif','webp'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($ext, $allowed, true) || strpos($mime, 'image/') !== 0) {
                $profileMessage = 'Invalid profile image format.';
            } else {
                $uploadDir = __DIR__ . '/../../uploads/employees';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $filename = 'profile_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                $dest = $uploadDir . '/' . $filename;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $uploadPhotoPath = 'uploads/employees/' . $filename;
                } else {
                    $profileMessage = 'Failed to upload profile image.';
                }
            }
        }
    }

    if ($profileMessage === '') {
        if ($newUsername === '') {
            $profileMessage = 'Username cannot be empty.';
        } else {
            $check = $conn->query("SELECT id FROM user_s WHERE username='" . $newUsername . "' AND id<>'" . $conn->real_escape_string($userId) . "' LIMIT 1");
            if ($check && $check->num_rows > 0) {
                $profileMessage = 'Username already taken.';
            } else {
                $sql = "UPDATE user_s SET username='" . $newUsername . "'";
                if ($hasPhotoColumn && $uploadPhotoPath !== null) {
                    $sql .= ", photo='" . $conn->real_escape_string($uploadPhotoPath) . "'";
                }
                if ($currentRole !== null) {
                    $sql .= ", role='" . $newRole . "'";
                }
                $sql .= " WHERE id='" . $conn->real_escape_string($userId) . "'";

                if ($conn->query($sql)) {
                    $_SESSION['username'] = $newUsername;
                    set_login_cookie('username', $newUsername);
                    if ($hasRoleColumn) {
                        $_SESSION['role'] = $newRole;
                        set_login_cookie('role', $newRole);
                    }

                    if ($uploadPhotoPath !== null) {
                        $currentPhoto = $uploadPhotoPath;
                    }
                    $profileMessage = 'Profile updated successfully.';
                    $currentUsername = $newUsername;
                    if ($hasRoleColumn) {
                        $currentRole = $newRole;
                    }
                } else {
                    $profileMessage = 'Failed to update profile.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css?v=appearance-theme">
</head>
<body <?= app_body_attributes() ?>>
    <div class="d-flex min-vh-100">
        <?php require_once __DIR__ . "/../../includes/sidebar.php"; ?>

        <div class="flex-grow-1 d-flex flex-column">
            <?php require_once __DIR__ . "/../../includes/navbar.php"; ?>

            <main class="flex-grow-1 p-4">
                <div class="container-fluid">
                    <div class="mb-4">
                        <h2 class="fw-bold mb-1">Profile</h2>
                        <p class="text-muted mb-0">Manage your account information.</p>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <?php $photoUrl = $currentPhoto ? app_base_url() . htmlspecialchars($currentPhoto) : app_base_url() . 'assets/images/profile.jpg'; ?>
                                <img src="<?= $photoUrl ?>" class="rounded-circle border border-2 " width="72" height="72" alt="Profile" style="object-fit: cover;">
                                <div>
                                    <h4 class="fw-bold mb-1"><?= htmlspecialchars($currentUsername) ?></h4>
                                    <p class="text-muted mb-0"><?= htmlspecialchars($currentRole ?: 'User') ?></p>
                                </div>
                            </div>

                            <?php if ($profileMessage): ?>
                                <div class="alert alert-info"><?= htmlspecialchars($profileMessage) ?></div>
                            <?php endif; ?>

                            <form method="post" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($currentUsername) ?>" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Profile Image</label>
                                        <input type="file" name="profile_photo" accept="image/*" class="form-control">
                                        <small class="text-muted">Upload a profile picture. JPEG, PNG, GIF, WEBP supported.</small>
                                    </div>
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Role</label>
                                        <?php
                                        $roles = ['Admin','Manager','HR','Employee'];
                                        $currentRoleEsc = htmlspecialchars($currentRole ?: '');
                                        ?>
                                        <select name="role" class="form-select">
                                            <?php if ($currentRoleEsc !== '' && !in_array($currentRoleEsc, $roles, true)): ?>
                                                <option selected><?= $currentRoleEsc ?></option>
                                            <?php endif; ?>
                                            <?php foreach ($roles as $r): ?>
                                                <option value="<?= $r ?>" <?= ($currentRole === $r) ? 'selected' : '' ?>><?= $r ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" name="save_profile" class="btn btn-primary">
                                        <i class="bi bi-save"></i>
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
