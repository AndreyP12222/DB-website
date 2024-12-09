<?php
include 'db.php'; 

// Отримуємо дані з таблиці courses_teacher
$query = "
    SELECT 
        ct.id, 
        c.name AS course_name, 
        t.firstName, 
        t.lastName
    FROM courses_teacher ct
    JOIN courses c ON ct.courses_id = c.id
    JOIN teacher t ON ct.teacher_id = t.id
    ORDER BY ct.id ASC
";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $teachers_on_courses = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $teachers_on_courses = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Вчителі на курсах</title>
</head>
<body>
    <div class="header-courses">
        <div class="zagol-v2">
            <h1>Вчителі на курсах</h1>
        </div>
        <div class="card-container">
            <div class="container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Курс</th>
                        <th>Вчитель</th>
                        <th>Дії</th>
                    </tr>
                    <?php foreach ($teachers_on_courses as $record): ?>
                    <tr>
                        <td><?= $record['id'] ?></td>
                        <td><?= $record['course_name'] ?></td>
                        <td><?= $record['firstName'] . ' ' . $record['lastName'] ?></td>
                        <td>
                            <a href="EDIT/teach_on_cours_edit.php?id=<?= $record['id'] ?>">Оновити</a>
                            <a href="DELETE/teach_on_cours_delete.php?id=<?= $record['id'] ?>" class="delete-button">Видалити</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <div class="buttons">
                    <button onclick="window.location.href='ADD/teacher_on_courses_add.php'">Додати вчителя на курс</button>
                    <button onclick="window.location.href='index.php'">Повернутись на головну</button>
                </div>
            </div>    
        </div>    
    </div>
</body>
</html>
