<?php
include '../db.php'; 

$teachersQuery = "SELECT id, CONCAT(firstName, ' ', lastName) AS fullName FROM teacher";
$teachersResult = $conn->query($teachersQuery);

$coursesQuery = "SELECT id, name FROM courses";
$coursesResult = $conn->query($coursesQuery);

// Додаємо вчителя на курс
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = (int)$_POST['teacher_id'];
    $course_id = (int)$_POST['courses_id'];

    if (empty($teacher_id) || empty($course_id)) {
        $error = "Заповніть всі поля.";
    } else {
           //Ін'єкція
        $stmt = $conn->prepare("INSERT INTO courses_teacher (teacher_id, courses_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $teacher_id, $course_id);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">alert("Вчителя успішно додано на курс!"); window.history.back();</script>';
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
    <title>Додати вчителя на курс</title>
</head>
<body>
<div class="header-add">
        <div class="zagol-v2">
            <h1>Додати вчителя на курс</h1>
        </div>
        <div class="card-container">
            <div class="container">
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                <form action="teacher_on_courses_add.php" method="post">
                    <label for="teacher_id">Вчитель</label>
                    <select name="teacher_id" id="teacher_id" required>
                        <option value="">Виберіть вчителя</option>
                        <?php
                        if ($teachersResult->num_rows > 0) {
                            while ($row = $teachersResult->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "' class='tooltip' data-tooltip='" . $row['fullName'] . "'>" . $row['id'] . " - " . $row['fullName'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>Немає вчителів</option>";
                        }
                        ?>
                    </select>

                    <label for="courses_id">Курс</label>
                    <select name="courses_id" id="courses_id" required>
                        <option value="">Виберіть курс</option>
                        <?php
                        if ($coursesResult->num_rows > 0) {
                            while ($row = $coursesResult->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "' class='tooltip' data-tooltip='" . $row['name'] . "'>" . $row['id'] . " - " . $row['name'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>Немає курсів</option>";
                        }
                        ?>
                    </select>

                    <button type="submit">Додати</button>
                </form>
                <button onclick="window.history.back()">Повернутись назад</button>
            </div>
        </div>
    </div>
</body>
</html>