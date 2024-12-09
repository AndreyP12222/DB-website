<?php
include 'db.php';

// Запит для отримання імен студентів, викладачів і адміністраторів
$query = "
    SELECT 
        user_lab.id,
        lab.name AS lab_name,
        student.firstName AS student_first_name,
        student.lastName AS student_last_name,
        teacher.firstName AS teacher_first_name,
        teacher.lastName AS teacher_last_name,
        administrator.firstName AS admin_first_name,
        administrator.lastName AS admin_last_name
    FROM 
        user_lab
    JOIN 
        laboratory_work lab ON user_lab.lab_id = lab.id
    JOIN 
        student ON user_lab.student_id = student.id
    JOIN 
        teacher ON user_lab.teacher_id = teacher.id
    JOIN 
        administrator ON user_lab.admin_id = administrator.id
    ORDER BY 
        user_lab.id ASC
";

$result = $conn->query($query);
$labUsers = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Користувачі лабораторних робіт</title>
</head>
<body>
    <div class="header-user">
        <div class="zagol-v2">
            <h2>Користувачі лабораторних робіт</h2>
        </div>
        <div class="card-container">
            <div class="container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Лабораторна робота</th>
                        <th>Студент</th>
                        <th>Викладач</th>
                        <th>Адміністратор</th>
                        <th>Дії</th>
                    </tr>
                    <?php foreach ($labUsers as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= $user['lab_name'] ?></td>
                            <td><?= $user['student_first_name'] . ' ' . $user['student_last_name'] ?></td>
                            <td><?= $user['teacher_first_name'] . ' ' . $user['teacher_last_name'] ?></td>
                            <td><?= $user['admin_first_name'] . ' ' . $user['admin_last_name'] ?></td>
                            <td>
                                <a href="EDIT/lab_user_edit.php?id=<?= $user['id'] ?>">Оновити</a>
                                <br>
                                <br>
                                <a href="DELETE/lab_user_delete.php?id=<?= $user['id'] ?>" class="delete-button">Видалити</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="buttons">
                    <button onclick="window.location.href='ADD/lab_user_add.php'">Додати нового користувача лабораторної роботи</button>
                    <button onclick="window.location.href='index.php'">Повернутись на головну</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>