<?php
include '../db.php';

$courseQuery = "SELECT id, name FROM courses";
$courseResult = $conn->query($courseQuery);
$courses = $courseResult->fetch_all(MYSQLI_ASSOC);

$studentQuery = "SELECT id, CONCAT(firstName, ' ', lastName) AS fullName FROM student";
$studentResult = $conn->query($studentQuery);
$students = $studentResult->fetch_all(MYSQLI_ASSOC);

// Перевірка наявності id в URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $query = "SELECT * FROM courses_student WHERE id = ?";
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

// Обробка форми для оновлення
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['courses_id'];
    $student_id = $_POST['student_id'];

    if (empty($course_id) || empty($student_id)) {
        $error = "Заповніть всі поля.";
    } else {
        $updateQuery = "UPDATE courses_student SET courses_id = ?, student_id = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("iii", $course_id, $student_id, $id);

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
    <title>Оновити запис студента на курс</title>
</head>
<body>
    <div class="header-courses">
        <div class="zagol-v2">
            <h1>Оновити запис студента на курс</h1>
        </div>
        <div class="card-container">
            <div class="container">
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                <form action="stud_on_cours_edit.php?id=<?= $record['id'] ?>" method="post">
                    <label for="courses_id">Курс</label>
                    <select name="courses_id" id="courses_id" required>
                        <option value="">Виберіть курс</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course['id'] ?>" <?= $course['id'] == $record['courses_id'] ? 'selected' : '' ?>>
                                <?= $course['id'] ?> - <?= $course['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="student_id">Студент</label>
                    <select name="student_id" id="student_id" required>
                        <option value="">Виберіть студента</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= $student['id'] ?>" <?= $student['id'] == $record['student_id'] ? 'selected' : '' ?> 
                                title="<?= $student['fullName'] ?>">
                                <?= $student['id'] ?> - <?= $student['fullName'] ?>
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
