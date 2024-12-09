<?php
include 'db.php';

// Отримуємо дані з таблиці laboratory_work та назви курсів
$query = "SELECT lw.id, lw.name, lw.number, c.name AS course_name
          FROM laboratory_work lw
          JOIN courses c ON lw.courses_id = c.id
          ORDER BY lw.id ASC";

$result = $conn->query($query);  
$labWorks = $result->fetch_all(MYSQLI_ASSOC); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторні роботи</title>
</head>
<body>
    <div class="header-lab">
        <div class="zagol-v2">
            <h1>Лабораторні роботи</h1>
        </div>
        <div class="card-container">
            <div class="container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Number</th>
                        <th>Course</th>
                        <th>Дії</th>
                    </tr>
                    <?php foreach ($labWorks as $work): ?>
                    <tr>
                        <td><?= $work['id'] ?></td>
                        <td><?= $work['name'] ?></td>
                        <td><?= $work['number'] ?></td>
                        <td><?= $work['course_name'] ?></td>
                        <td>
                            <a href="EDIT/lab_work_edit.php?id=<?= $work['id'] ?>">Оновити</a>
                            <a href="DELETE/lab_work_delete.php?id=<?= $work['id'] ?>" class="delete-button">Видалити</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <div class="buttons">
                    <button onclick="window.location.href='ADD/lab_work_add.php'">Додати нову лабораторну роботу</button>
                    <button onclick="window.location.href='index.php'">Повернутись на головну</button>
                </div>
            </div>    
        </div>
    </div>
</body>
</html>
