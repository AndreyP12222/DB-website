<?php
include '../db.php'; 


$studentsQuery = "SELECT id, CONCAT(firstName, ' ', lastName) AS fullName FROM student";
$studentsResult = $conn->query($studentsQuery);

$teachersQuery = "SELECT id, CONCAT(firstName, ' ', lastName) AS fullName FROM teacher";
$teachersResult = $conn->query($teachersQuery);


$labsQuery = "SELECT id, name FROM laboratory_work";
$labsResult = $conn->query($labsQuery);

// Додаємо користувача лабораторної роботи
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lab_id = (int)$_POST['lab_id'];
    $student_id = (int)$_POST['student_id'];
    $teacher_id = (int)$_POST['teacher_id'];
    $admin_id = (int)$_POST['admin_id'];

    if (empty($lab_id) || empty($student_id) || empty($teacher_id) || empty($admin_id)) {
        $error = "Заповніть всі поля.";
    } else {
         //Ін'єкція
        $stmt = $conn->prepare("INSERT INTO user_lab (lab_id, student_id, teacher_id, admin_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $lab_id, $student_id, $teacher_id, $admin_id);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">alert("Користувача лабораторної роботи успішно додано!"); window.history.back();</script>';
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
    <title>Додати Користувача Лабораторної Роботи</title>
</head>
<body>
<div class="header-add">
    <div class="zagol-v2">
        <h1>Додати Користувача Лабораторної Роботи</h1>
    </div>
    <div class="card-container">
        <div class="container">
            <?php if (!empty($error)): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>
            <form action="lab_user_add.php" method="post">
                <label for="lab_id">Лабораторна робота</label>
                <select name="lab_id" id="lab_id" required>
                    <option value="">Виберіть лабораторну роботу</option>
                    <?php
                    if ($labsResult->num_rows > 0) {
                        while ($row = $labsResult->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "' class='tooltip' data-tooltip='" . $row['name'] . "'>" . $row['id'] . " - " . $row['name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Немає лабораторних робіт</option>";
                    }
                    ?>
                </select>

                <label for="student_id">Студент</label>
                <select name="student_id" id="student_id" required>
                    <option value="">Виберіть студента</option>
                    <?php
                    if ($studentsResult->num_rows > 0) {
                        while ($row = $studentsResult->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "' class='tooltip' data-tooltip='" . $row['fullName'] . "'>" . $row['id'] . " - " . $row['fullName'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Немає студентів</option>";
                    }
                    ?>
                </select>
                <br>
                <label for="teacher_id">Вчитель</label>
                <select name="teacher_id" id="teacher_id" required>
                    <option value="">Виберіть вчителя</option>
                    <?php
                    if ($teachersResult->num_rows > 0) {
                        while ($row = $teachersResult->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "' class='tooltip' data-tooltip='" . $row['fullName'] . "'>" . $row['id'] . " - " . $row['fullName'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Немає вчителів</option>";
                    }
                    ?>
                </select>

                <label for="admin_id">Адміністратор</label>
                <select name="admin_id" id="admin_id" required>
                    <option value="">Виберіть адміністратора</option>
                    <?php
                    // Отримуємо список адміністраторів
                    $adminQuery = "SELECT id, CONCAT(firstName, ' ', lastName) AS fullName FROM administrator";
                    $adminResult = $conn->query($adminQuery);
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