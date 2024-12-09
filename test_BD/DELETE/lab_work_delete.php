<?php
include '../db.php';

// Отримуємо ID лабораторної роботи
$id = $_GET['id'];

// Перевіряємо, чи ID є числом
if (!is_numeric($id)) {
    die("Невірний ID");
}

// Видаляємо лабораторну роботу з бази
$query = "DELETE FROM laboratory_work WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo '<script type="text/javascript">alert("Дані успішно видалено!"); window.history.back();</script>';
    exit;
} else {
    $error = "Сталася помилка: " . $stmt->error;
}
$stmt->close();
?>