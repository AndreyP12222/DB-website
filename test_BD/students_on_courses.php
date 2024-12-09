<?php
include 'db.php'; 

// Отримуємо дані з таблиць через об’єднання
$query = "
    SELECT 
        cs.id, 
        c.name AS course_name, 
        s.firstName, 
        s.lastName 
    FROM courses_student cs
    JOIN courses c ON cs.courses_id = c.id
    JOIN student s ON cs.student_id = s.id
    ORDER BY cs.id ASC
";
$result = $conn->query($query);

if ($result->num_rows > 0) {

    $students_on_courses = $result->fetch_all(MYSQLI_ASSOC); 
} else {
    $students_on_courses = []; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Студенти на курсах</title>
</head>
<body>
    <div class="header-students-courses">
        <div class="zagol-v2">
            <h1>Студенти на курсах</h1>
        </div>
        <div class="card-container">
            <div class="container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Курс</th>
                        <th>Студент</th>
                        <th>Дії</th>
                    </tr>
                    <?php foreach ($students_on_courses as $record): ?>
                        <tr>
                            <td><?= $record['id'] ?></td>
                            <td><?= $record['course_name'] ?></td>
                            <td><?= $record['firstName'] . ' ' . $record['lastName'] ?></td>
                            <td>
                                <a href="EDIT/stud_on_cours_edit.php?id=<?= $record['id'] ?>">Оновити</a>
                                <a href="DELETE/stud_on_cours_delete.php?id=<?= $record['id'] ?>" class="delete-button">Видалити</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="buttons">
                    <button onclick="window.location.href='ADD/student_on_courses_add.php'">Додати студента на курс</button>
                    <button onclick="window.location.href='index.php'">Повернутись на головну</button>
                </div>
            </div>    
        </div>
    </div>
</body>
</html>
