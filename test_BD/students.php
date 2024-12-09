<?php
include 'db.php';

// Отримуємо дані з таблиці students
$query = "SELECT * FROM student ORDER BY id ASC";
$result = $conn->query($query);
$students = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Студенти</title>
</head>
<body>
    <div class="header-stud">
        <div class="zagol-v2">
            <h2>Студенти</h2>
        </div>
        <div class="card-container">
            <div class="container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>NickName</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone</th>
                        <th>Age</th>
                        <th>Admin ID</th>
                        <th>Дії</th>
                    </tr>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= $student['id'] ?></td>
                            <td><?= $student['nickName'] ?></td>
                            <td><?= $student['firstName'] ?></td>
                            <td><?= $student['lastName'] ?></td>
                            <td><?= $student['phone'] ?></td>
                            <td><?= $student['age'] ?></td>
                            <td><?= $student['admin_id'] ?></td>
                            <td>
                                <a href="EDIT/students_edit.php?id=<?= $student['id'] ?>">Оновити</a>
                                <a href="DELETE/students_delete.php?id=<?= $student['id'] ?>"class="delete-button">Видалити</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="buttons">
                    <button onclick="window.location.href='ADD/student_add.php'">Додати нового студента</button>
                    <button onclick="window.location.href='index.php'">Повернутись на головну</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
