<?php
include '../db.php'; 

$studentsQuery = "SELECT id, CONCAT(firstName, ' ', lastName) AS fullName FROM student";
$studentsResult = $conn->query($studentsQuery);

$courQuery = "SELECT id, name FROM courses";
$courResult = $conn->query($courQuery);

// Додаємо студента на курс
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int)$_POST['student_id'];
    $courses_id = (int)$_POST['courses_id'];

    if (empty($student_id) || empty($courses_id)) {
        $error = "Заповніть всі поля.";
    } else {
           //Ін'єкція
        $stmt = $conn->prepare("INSERT INTO courses_student (student_id, courses_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $student_id, $courses_id);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">alert("Студента успішно додано на курс!"); window.history.back();</script>';
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
    <title>Додати студента на курс</title>
</head>
<body>
<div class="header-add">
        <div class="zagol-v2">
            <h1>Додати студента на курс</h1>
        </div>
        <div class="card-container">
            <div class="container">
                <?php if (!empty($error)): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                <form action="student_on_courses_add.php" method="post">
                    <label for="student_id">Студент</label>
                    <select name="student_id" id="student_id" required>
                        <option value="">Виберіть студента</option>
                        <?php
                        if ($studentsResult->num_rows > 0) {
                            while ($row = $studentsResult->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "' class='tooltip' data-tooltip='" . $row['fullName'] . "'>" . $row['id'] . " - " . $row['fullName'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>Немає студентів</option>";
                        }
                        ?>
                    </select>

                    <label for="courses_id">Курс</label>
                    <select name="courses_id" id="courses_id" required>
                        <option value="">Виберіть курс</option>
                        <?php
                        if ($courResult->num_rows > 0) {
                            while ($row = $courResult->fetch_assoc()) {
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