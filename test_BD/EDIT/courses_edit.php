<?php
include '../db.php'; 

$adminQuery = "SELECT id, nickName FROM administrator"; 
$adminResult = $conn->query($adminQuery);
$admins = $adminResult->fetch_all(MYSQLI_ASSOC);

// Перевірка наявності id в URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $query = "SELECT * FROM courses WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $course = $result->fetch_assoc();
    } else {
        echo "Курс не знайдено.";
        exit;
    }
} else {
    echo "Невірний параметр ID.";
    exit;
}

// Обробка форми для оновлення
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $admin_id = $_POST['admin_id'];

    if (empty($name) || empty($admin_id)) {
        $error = "Заповніть всі поля.";
    } else {
        $updateQuery = "UPDATE courses SET name = ?, admin_id = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sii", $name, $admin_id, $id);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">alert("Дані успішно оновлено!"); window.history.back();</script>';
            exit;
        } else {
            $error = "Сталася помилка: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оновити Курс</title>
</head>
<body>
    <div class="header-courses">
        <div class="zagol-v2">
            <h1>Оновити Курс</h1>
        </div>
        <div class="card-container">
            <div class="container">
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                <form action="courses_edit.php?id=<?= $course['id'] ?>" method="post">
                    <label for="name">Назва курсу</label>
                    <input type="text" name="name" id="name" required>

                    <label for="admin_id">Admin ID</label>
                    <select name="admin_id" id="admin_id" required>
                        <option value="">Виберіть адміністратора</option>
                        <?php foreach ($admins as $admin): ?>
                            <option value="<?= $admin['id'] ?>" <?= $admin['id'] == $course['admin_id'] ? 'selected' : '' ?>>
                                <?= $admin['nickName'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit">Оновити</button>
                </form>
                <div class="buttons">
                    <button onclick="window.history.back()">Повернутись до списку</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
