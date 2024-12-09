<?php
include '../db.php';

// Перевірка, чи передано ID студента
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Перевірка, чи ID є числом
    if (!is_numeric($id)) {
        die("Невірний ID");
    }

    $query = "SELECT * FROM student WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        die("Студент не знайдений.");
    }

    // Обробка форми оновлення
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nickName = $_POST['nickName'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $phone = $_POST['phone'];
        $age = $_POST['age'];
        $admin_id = $_POST['admin_id'];

        // Оновлюємо дані в базі
        $updateQuery = "UPDATE student SET nickName = ?, firstName = ?, lastName = ?, phone = ?, age = ?, admin_id = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssssiii", $nickName, $firstName, $lastName, $phone, $age, $admin_id, $id);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">alert("Дані успішно оновлено!"); window.history.back();</script>';
            exit;
        } else {
            $error = "Сталася помилка: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    die("ID студента не передано.");
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оновити студента</title>
</head>
<body>
    <div class="header-stud">
        <div class="zagol-v2">
            <h2>Оновити студента</h2>
        </div>
        <div class="card-container">
            <div class="container">
                <?php if (isset($error)): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                <form action="students_edit.php?id=<?= $student['id'] ?>" method="post">
                    <label for="nickName">NickName</label>
                    <input type="text" name="nickName" id="nickName" value="<?= $student['nickName'] ?>" required>

                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" id="firstName" value="<?= $student['firstName'] ?>" required>

                    <label for="lastName">Last Name</label>
                    <input type="text" name="lastName" id="lastName" value="<?= $student['lastName'] ?>" required>
                    <br>
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone" value="<?= $student['phone'] ?>" required>

                    <label for="age">Age</label>
                    <input type="number" name="age" id="age" value="<?= $student['age'] ?>" required>

                    <label for="admin_id">Admin ID</label>
                    <input type="number" name="admin_id" id="admin_id" value="<?= $student['admin_id'] ?>" required>

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