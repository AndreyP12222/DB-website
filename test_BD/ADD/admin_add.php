<?php
include '../db.php'; 

// Очищення введених даних
function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Отримуємо дані з форми
    $nickName = $_POST['nickName'];
    $firstName = sanitize_input($_POST['firstName']);
    $lastName = sanitize_input($_POST['lastName']);

    // Фільтрація
    $nickName = filter_var($nickName, FILTER_SANITIZE_STRING);
    $firstName = filter_var($firstName, FILTER_SANITIZE_STRING);
    $lastName = filter_var($lastName, FILTER_SANITIZE_STRING);

    // Валідація
    $nickName = filter_var($nickName, FILTER_SANITIZE_STRING);
    if (empty($nickName) || strlen($nickName) < 3 || strlen($nickName) > 20) {
        echo "Нікнейм має бути від 3 до 20 символів";
        exit; 
    }
    if (empty($firstName) || empty($lastName)) {
        echo "Заповніть всі поля!";
        exit;
    }
    // Ін'єкція
    $stmt = $conn->prepare("INSERT INTO administrator (nickName, firstName, lastName) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nickName, $firstName, $lastName);

    if ($stmt->execute()) {
        echo '<script type="text/javascript">alert("Адміністратора успішно додано!"); window.history.back();</script>';
        exit;
    } else {
        echo "Помилка: " . $stmt->error;
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати адміністратора</title>
</head>
<body>
    <div class="header-add">
        <div class="zagol-v2">
            <h1>Додати адміністратора</h1>
        </div>
        <div class="card-container">
            <div class="container">
                <form action="admin_add.php" method="post">
                    <label for="nickName">NickName</label>
                    <input type="text" name="nickName" id="nickName" required>
                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" id="firstName" required>
                    <label for="lastName">Last Name</label>
                    <input type="text" name="lastName" id="lastName" required>
                    <button type="submit">Додати</button>
                </form>
                <button onclick="window.history.back()">Повернутись назад</button>
            </div>
        </div>
    </div>
</body>
</html>

