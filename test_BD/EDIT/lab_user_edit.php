<?php
include '../db.php';

$labsQuery = "SELECT id, name FROM laboratory_work";
$labsResult = $conn->query($labsQuery);
$labs = $labsResult->fetch_all(MYSQLI_ASSOC);

$coursesQuery = "SELECT id, name FROM courses";
$coursesResult = $conn->query($coursesQuery);
$courses = $coursesResult->fetch_all(MYSQLI_ASSOC);

$teachersQuery = "SELECT id, firstName, lastName FROM teacher";
$teachersResult = $conn->query($teachersQuery);
$teachers = $teachersResult->fetch_all(MYSQLI_ASSOC);

$studentsQuery = "SELECT id, firstName, lastName FROM student";
$studentsResult = $conn->query($studentsQuery);
$students = $studentsResult->fetch_all(MYSQLI_ASSOC);

// Перевірка, чи передано ID користувача
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Перевірка, чи ID є числом
    if (!is_numeric($id)) {
        die("Невірний ID");
    }
    
    $query = "SELECT * FROM user_lab WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("Користувач не знайдений.");
    }

    // Обробка форми оновлення
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $lab_id = $_POST['lab_id'];
        $student_id = $_POST['student_id'];
        $teacher_id = $_POST['teacher_id'];
        $admin_id = $_POST['admin_id'];

        // Оновлюємо дані в базі
        $updateQuery = "UPDATE user_lab SET lab_id = ?, student_id = ?, teacher_id = ?, admin_id = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("iiiii", $lab_id, $student_id, $teacher_id, $admin_id, $id);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">alert("Дані успішно оновлено!"); window.history.back();</script>';
            exit;
        } else {
            $error = "Сталася помилка: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    die("ID користувача не передано.");
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оновити користувача лабораторної роботи</title>
</head>
<body>
    <div class="header-user">
        <div class="zagol-v2">
            <h2>Оновити користувача лабораторної роботи</h2>
        </div>
        <div class="card-container">
            <div class="container">
                <?php if (isset($error)): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                <form action="lab_user_edit.php?id=<?= $user['id'] ?>" method="post">
                    <label for="lab_id">Lab ID</label>
                    <select name="lab_id" id="lab_id" required>
                        <?php foreach ($labs as $lab): ?>
                            <option value="<?= $lab['id'] ?>" <?= $lab['id'] == $user['lab_id'] ? 'selected' : '' ?>>
                                <?= $lab['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="student_id">Student ID</label>
                    <select name="student_id" id="student_id" required>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= $student['id'] ?>" <?= $student['id'] == $user['student_id'] ? 'selected' : '' ?>>
                                <?= $student['firstName'] . " " . $student['lastName'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="teacher_id">Teacher ID</label>
                    <select name="teacher_id" id="teacher_id" required>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?= $teacher['id'] ?>" <?= $teacher['id'] == $user['teacher_id'] ? 'selected' : '' ?>>
                                <?= $teacher['firstName'] . " " . $teacher['lastName'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <br>        
                    <label for="admin_id">Admin ID</label>
                    <input type="number" name="admin_id" id="admin_id" value="<?= $user['admin_id'] ?>" required>

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

