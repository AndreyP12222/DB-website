<?php
include '../db.php'; 

$adminsQuery = "SELECT id, CONCAT(firstName, ' ', lastName) AS fullName FROM administrator";
$adminsResult = $conn->query($adminsQuery);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отримуємо дані з форми
    $name = trim($_POST['name']);
    $admin_id = (int)$_POST['admin_id']; 

    // Фільтрація
    $name = filter_var(trim($name), FILTER_SANITIZE_STRING);
    $admin_id = filter_var($admin_id, FILTER_VALIDATE_INT);

    // Валідація
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    if (empty($name) || strlen($name) < 3 || strlen($name) > 100) {
        $error = "Назва курсу має бути від 3 до 100 символів.";
    } elseif (empty($admin_id)) {
        $error = "Оберіть адміністратора.";
    } else {
        // Ін'єкція
        $stmt = $conn->prepare("INSERT INTO courses (name, admin_id) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $admin_id); 

        if ($stmt->execute()) {
            echo '<script type="text/javascript">alert("Курс успішно додано!"); window.history.back();</script>';
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
    <title>Додати курс</title>
</head>
<body>
    <div class="header-add">
        <div class="zagol-v2">
            <h1>Додати курс</h1>
        </div>
        <div class="card-container">
            <div class="container">
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                <form action="course_add.php" method="post">
                    <label for="name">Назва курсу</label>
                    <input type="text" name="name" id="name" required>

                    <label for="admin_id">Адміністратор</label>
                    <select name="admin_id" id="admin_id" required>
                        <option value="">Виберіть адміністратора</option>
                        <?php
                        if ($adminsResult->num_rows > 0) {
                            while ($row = $adminsResult->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "'>" . $row['fullName'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>Немає адміністраторів</option>";
                        }
                        ?>
                    </select>

                    <button type="submit">Додати</button>
                </form>
                <button onclick="window.history.back()">Повернутись назад</button>
            </div>
        </div>
    </div>
</body>
</html>
