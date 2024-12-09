<?php
include '../db.php'; 

// Перевірка, чи є `id` у запиті
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Видалення запису
    $delete_query = "DELETE FROM courses_student WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo '<script type="text/javascript">alert("Дані успішно видалено!"); window.history.back();</script>';
        exit;
    } else {
        $error = "Сталася помилка: " . $stmt->error;
    }
} else {
    echo "ID не вказано!";
    exit;
}
?>