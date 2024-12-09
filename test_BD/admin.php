<?php
include 'db.php';

//Cтандартної очистки вхідних даних
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

//Превірка успішності виконання
$query = "SELECT * FROM administrator ORDER BY id ASC";
$result = $conn->query($query);

if ($result) {
    $admins = $result->fetch_all(MYSQLI_ASSOC);
} else {
    die("Помилка виконання запиту: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Адміністратор</title> 
</head>
<body>
    <div class="header-admin">
        <div class="zagol-v2">
            <h1>Адміністратори</h1>
        </div>
        <div class="card-container">
            <div class="container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>NickName</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Дії</th>
                    </tr>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?= $admin['id'] ?></td>
                            <td><?= $admin['nickName'] ?></td>
                            <td><?= $admin['firstName'] ?></td>
                            <td><?= $admin['lastName'] ?></td>
                            <td>
                                <a href="EDIT/admin_edit.php?id=<?= $admin['id'] ?>">Оновити</a>
                                <a href="DELETE/admin_delete.php?id=<?= $admin['id'] ?>"class="delete-button">Видалити</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <div class="buttons">
                    <button onclick="window.location.href='ADD/admin_add.php?table=admin'">Додати нового адміністратора</button>
                    <button onclick="window.location.href='index.php'">Повернутись на головну</button>
                </div>
            </div>
        </div>    
    </div>
</body>
</html>

