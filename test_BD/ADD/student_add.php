<?php
include '../db.php'; 

$adminQuery = "SELECT id, CONCAT(firstName, ' ', lastName) AS fullName FROM administrator";
$adminResult = $conn->query($adminQuery);

// Додаємо студента
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickName = $_POST['nick_name'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phone = $_POST['phone'];
    $age = (int)$_POST['age'];
    $admin_id = (int)$_POST['admin_id'];

    // Фільтрація
    $nickName = filter_var(trim($nickName), FILTER_SANITIZE_STRING);
    $firstName = filter_var(trim($firstName), FILTER_SANITIZE_STRING);
    $lastName = filter_var(trim($lastName), FILTER_SANITIZE_STRING);
    $phone = filter_var(trim($phone), FILTER_SANITIZE_STRING);
    $age = filter_var($age, FILTER_VALIDATE_INT);
    $admin_id = filter_var($admin_id, FILTER_VALIDATE_INT);

    // Валідація
    if (empty($nickName) || strlen($nickName) < 3 || strlen($nickName) > 20) {
        $error = "Нікнейм має бути від 3 до 20 символів.";
    } elseif (empty($firstName) || strlen($firstName) < 2 || strlen($firstName) > 50) {
        $error = "Ім'я має бути від 2 до 50 символів.";
    } elseif (empty($lastName) || strlen($lastName) < 2 || strlen($lastName) > 50) {
        $error = "Прізвище має бути від 2 до 50 символів.";
    } elseif (empty($phone) || !preg_match("/^\+?[0-9\s\-]{7,15}$/", $phone)) {
        $error = "Введіть коректний номер телефону.";
    } elseif ($age === false || $age < 16 || $age > 100) {
        $error = "Вік має бути числом від 16 до 100.";
    } elseif ($admin_id === false || $admin_id <= 0) {
        $error = "Виберіть адміністратора.";
    } elseif (empty($firstName) || empty($lastName) || empty($phone) || empty($age) || empty($admin_id)) {
        $error = "Заповніть всі поля.";
    } else {
        // Ін'єкція
        $stmt = $conn->prepare("INSERT INTO student (nickName, `firstName`, `lastName`, phone, age, `admin_id`) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssis", $nickName, $firstName, $lastName, $phone, $age, $admin_id);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">alert("Студента успішно додано!"); window.history.back();</script>';
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
    <title>Додати Студента</title>
</head>
<body>
<div class="header-add">
    <div class="zagol-v2">
        <h1>Додати Студента</h1>
    </div>
    <div class="card-container">
        <div class="container">
            <?php if (!empty($error)): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>
            <form action="student_add.php" method="post">
                <label for="nick_name">Нікнейм</label>
                <input type="text" id="nick_name" name="nick_name" required>

                <label for="first_name">Ім'я</label>
                <input type="text" id="first_name" name="first_name" required>

                <label for="last_name">Прізвище</label>
                <input type="text" id="last_name" name="last_name" required>
                <br>
                <label for="phone">Телефон</label>
                <input type="text" id="phone" name="phone" required>
            
                <label for="age">Вік</label>
                <input type="number" id="age" name="age" min="1" required>

                <label for="admin_id">Адміністратор</label>
                <select name="admin_id" id="admin_id" required>
                    <option value="">Виберіть адміністратора</option>
                    <?php
                    if ($adminResult->num_rows > 0) {
                        while ($row = $adminResult->fetch_assoc()) {
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