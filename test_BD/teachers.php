<?php
include 'db.php';

// Отримуємо дані з таблиці teachers
$query = "SELECT * FROM teacher ORDER BY id ASC";
$result = $conn->query($query);
$teachers = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Викладачі</title>
</head>
<body>
    <div class="header-teacher">
        <div class="zagol-v2">
            <h2>Викладачі</h2>
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
                    <?php foreach ($teachers as $teacher): ?>
                        <tr>
                            <td><?= $teacher['id'] ?></td>
                            <td><?= $teacher['nickName'] ?></td>
                            <td><?= $teacher['firstName'] ?></td>
                            <td><?= $teacher['lastName'] ?></td>
                            <td><?= $teacher['phone'] ?></td>
                            <td><?= $teacher['age'] ?></td>
                            <td><?= $teacher['admin_id'] ?></td>
                            <td>
                                <a href="EDIT/teachers_edit.php?id=<?= $teacher['id'] ?>">Оновити</a>
                                <a href="DELETE/teachers_delete.php?id=<?= $teacher['id'] ?>"class="delete-button">Видалити</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="buttons">
                    <button onclick="window.location.href='ADD/teacher_add.php'">Додати нового викладача</button>
                    <button onclick="window.location.href='index.php'">Повернутись на головну</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>