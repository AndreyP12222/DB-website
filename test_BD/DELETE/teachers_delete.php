<?php
include '../db.php';

// Перевірка, чи передано ID викладача
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Перевірка, чи ID є числом
    if (!is_numeric($id)) {
        die("Невірний ID");
    }

    // Видалення викладача з бази даних
    $deleteQuery = "DELETE FROM teacher WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo '<script type="text/javascript">alert("Дані успішно видалено!"); window.history.back();</script>';
        exit;
    } else {
        $error = "Сталася помилка: " . $stmt->error;
    }
} else {
    die("ID викладача не передано.");
}