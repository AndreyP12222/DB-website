<?php
include 'db.php'; 

// Отримуємо дані з таблиці courses
$query = "SELECT * FROM courses ORDER BY id ASC";
$result = $conn->query($query);  
$courses = $result->fetch_all(MYSQLI_ASSOC);  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Курси</title>
</head>
<body>
    <div class="header-courses">
        <div class="zagol-v2">
            <h1>Курси</h1>
        </div>
        <div class="card-container">
            <div class="container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Назва курсу</th>
                        <th>Admin ID</th>
                        <th>Дії</th>
                    </tr>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?= $course['id'] ?></td>
                            <td><?= $course['name'] ?></td>
                            <td><?= $course['admin_id'] ?></td>
                            <td>
                                <a href="EDIT/courses_edit.php?id=<?= $course['id'] ?>">Оновити</a>
                                <a href="DELETE/courses_delete.php?id=<?= $course['id'] ?>"class="delete-button">Видалити</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="buttons">
                    <button onclick="window.location.href='ADD/course_add.php?table=courses'">Додати новий курс</button>
                    <button onclick="window.location.href='index.php'">Повернутись на головну</button>
                </div>
            </div>    
        </div>    
    </div>
</body>
</html>