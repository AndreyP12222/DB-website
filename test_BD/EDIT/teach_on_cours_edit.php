<?php
include '../db.php'; 

$courseQuery = "SELECT id, name FROM courses";
$courseResult = $conn->query($courseQuery);
$courses = $courseResult->fetch_all(MYSQLI_ASSOC);

$teacherQuery = "SELECT id, CONCAT(firstName, ' ', lastName) AS fullName FROM teacher";
$teacherResult = $conn->query($teacherQuery);
$teachers = $teacherResult->fetch_all(MYSQLI_ASSOC);

// Перевіряємо, чи переданий ID
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Отримуємо дані запису для редагування
    $query = "SELECT * FROM courses_teacher WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $record = $result->fetch_assoc();
    } else {
        echo "Запис не знайдено.";
        exit;
    }
} else {
    echo "Невірний параметр ID.";
    exit;
}

// Обробляємо форму для оновлення
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['courses_id'];
    $teacher_id = $_POST['teacher_id'];

    if (empty($course_id) || empty($teacher_id)) {
        $error = "Заповніть всі поля.";
    } else {
        $updateQuery = "UPDATE courses_teacher SET courses_id = ?, teacher_id = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("iii", $course_id, $teacher_id, $id);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">alert("Дані успішно оновлено!"); window.history.back();</script>';
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
    <title>Оновити запис вчителя на курс</title>
</head>
<body>
    <div class="header-courses">
        <div class="zagol-v2">
            <h1>Оновити запис вчителя на курс</h1>
        </div>
        <div class="card-container">
            <div class="container">
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                <form action="teach_on_cours_edit.php?id=<?= $record['id'] ?>" method="post">
                    <label for="courses_id">Курс</label>
                    <select name="courses_id" id="courses_id" required>
                        <option value="">Виберіть курс</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id'] ?>" <?= $course['id'] == $record['courses_id'] ? 'selected' : '' ?>>
                                <?= $course['id'] ?> - <?= $course['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="teacher_id">Вчитель</label>
                    <select name="teacher_id" id="teacher_id" required>
                        <option value="">Виберіть вчителя</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?= $teacher['id'] ?>" <?= $teacher['id'] == $record['teacher_id'] ? 'selected' : '' ?>
                                title="<?= $teachert['fullName'] ?>">
                                <?= $teacher['id'] ?> - <?= $teacher['fullName'] ?>
                            </option>
                        <?php endforeach; ?>
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
