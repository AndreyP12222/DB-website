<?php
include '../db.php'; 

$courQuery = "SELECT id, name FROM courses";
$courResult = $conn->query($courQuery);

// Додаємо лабораторну роботу
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lab_name = $_POST['lab_name'];
    $lab_number = (int)$_POST['lab_number'];
    $courses_id = (int)$_POST['courses_id'];

    if (empty($lab_name) || empty($lab_number) || empty($courses_id)) {
        $error = "Заповніть всі поля.";
    } else {
           //Ін'єкція
        $stmt = $conn->prepare("INSERT INTO laboratory_work (name, number, courses_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $lab_name, $lab_number, $courses_id);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">alert("Лабораторну роботу успішно додано!"); window.history.back();</script>';
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
    <title>Додати лабораторну роботу</title>
</head>
<body>
<div class="header-add">
    <div class="zagol-v2">
        <h1>Додати лабораторну роботу</h1>
    </div>
    <div class="card-container">
        <div class="container">
            <?php if (!empty($error)): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>
            <form action="lab_work_add.php" method="post">
                <label for="lab_name">Назва лабораторної роботи</label>
                <input type="text" id="lab_name" name="lab_name" required>

                <label for="lab_number">Номер лабораторної роботи</label>
                <input type="number" id="lab_number" name="lab_number" min="1" required>
                <br>
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