<?php
include '../db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Видалення адміністратора
    $query = "DELETE FROM administrator WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo '<script type="text/javascript">alert("Дані успішно видалено!"); window.history.back();</script>';
        exit;
    } else {
        $error = "Сталася помилка: " . $stmt->error;
    }
}
?>