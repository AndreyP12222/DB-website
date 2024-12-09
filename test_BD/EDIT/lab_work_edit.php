<?php
include '../db.php';

// Отримуємо ID лабораторної роботи
$id = $_GET['id'];


if (!is_numeric($id)) {
    die("Невірний ID");
}

$query = "SELECT * FROM laboratory_work WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$work = $result->fetch_assoc();

if (!$work) {
    die("Лабораторна робота не знайдена.");
}

$coursesQuery = "SELECT id, name FROM courses ORDER BY name";
$coursesResult = $conn->query($coursesQuery);

// Обробка форми оновлення
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $number = $_POST['number'];
    $courses_id = $_POST['courses_id'];

    // Оновлюємо дані в базі
    $updateQuery = "UPDATE laboratory_work SET name = ?, number = ?, courses_id = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssis", $name, $number, $courses_id, $id);

    if ($stmt->execute()) {
        echo '<script type="text/javascript">alert("Дані успішно оновлено!"); window.history.back();</script>';
        exit;
    } else {
        $error = "Сталася помилка: " . $stmt->error;
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
    <title>Оновити лабораторну роботу</title>
    <style>
        .select-container {
            position: relative;
        }
        select {
            width: 100%;
            padding: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="header-lab-work">
        <div class="zagol-v2">
            <h1>Оновити лабораторну роботу</h1>
        </div>
        <div class="card-container">
            <div class="container">
                <?php if (isset($error)): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                <form action="lab_work_edit.php?id=<?= $work['id'] ?>" method="post">
                    <label for="name">Назва</label>
                    <input type="text" name="name" id="name" value="<?= $work['name'] ?>" required>

                    <label for="number">Номер</label>
                    <input type="number" name="number" id="number" value="<?= $work['number'] ?>" required>
                     
                    <label for="courses_id">ID курсу</label>
                    <select name="courses_id" id="courses_id" required>
                        <option value="">Виберіть курс</option>
                        <?php while ($course = $coursesResult->fetch_assoc()): ?>
                            <option value="<?= $course['id'] ?>" <?= $course['id'] == $work['courses_id'] ? 'selected' : '' ?>>
                                <?= $course['id'] ?> - <?= $course['name'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

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
