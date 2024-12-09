<?php
include '../db.php'; 

// Перевіряємо, чи переданий ID
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Видаляємо запис
    $query = "DELETE FROM courses_teacher WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo '<script type="text/javascript">alert("Дані успішно видалено!"); window.history.back();</script>';
        exit;
    } else {
        $error = "Сталася помилка: " . $stmt->error;
    }
} else {
    echo "Невірний параметр ID.";
}
?>