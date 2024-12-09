<?php
include '../db.php';

// Перевірка наявності id в URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $query = "SELECT * FROM administrator WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
    } else {
        echo "Запис не знайдено.";
        exit;
    }
} else {
    echo "Невірний параметр ID.";
    exit;
}

// Обробка форми для оновлення
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickName = $_POST['nickName'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];

    if (empty($nickName) || empty($firstName) || empty($lastName)) {
        $error = "Заповніть всі поля.";
    } else {
        $updateQuery = "UPDATE administrator SET nickName = ?, firstName = ?, lastName = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssi", $nickName, $firstName, $lastName, $id);

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
    <title>Оновити Адміністратора</title>
</head>
<body>
    <div class="header-admin">
        <div class="zagol-v2">
            <h1>Оновити Адміністратора</h1>
        </div>
        <div class="card-container">
            <div class="container">
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                <form action="admin_edit.php?id=<?= $admin['id'] ?>" method="post">
                    <label for="nickName">NickName</label>
                    <input type="text" name="nickName" id="nickName" value="<?= $admin['nickName'] ?>" required>

                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" id="firstName" value="<?= $admin['firstName'] ?>" required>

                    <label for="lastName">Last Name</label>
                    <input type="text" name="lastName" id="lastName" value="<?= $admin['lastName'] ?>" required>

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