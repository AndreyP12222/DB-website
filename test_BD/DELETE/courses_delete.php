<?php
include '../db.php'; 

// Перевірка наявності id в URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Виконання запиту для видалення курсу
    $deleteQuery = "DELETE FROM courses WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo '<script type="text/javascript">alert("Дані успішно видалено!"); window.history.back();</script>';
        exit;
    } else {
        $error = "Сталася помилка: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Невірний параметр ID.";
    exit;
}
?>