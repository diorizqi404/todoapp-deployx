<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $title = $_POST['title'] ?? '';
        if (!empty($title)) {
            $stmt = db()->prepare("INSERT INTO todos (user_id, title) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $title]);
        }
    } elseif ($action === 'toggle') {
        $id = $_POST['id'] ?? 0;
        $stmt = db()->prepare("UPDATE todos SET completed = NOT completed WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? 0;
        $stmt = db()->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
    } elseif ($action === 'logout') {
        session_destroy();
        header('Location: index.php');
        exit;
    }
    
    header('Location: dashboard.php');
    exit;
}

// Get todos
$stmt = db()->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="card" style="max-width: 600px;">
        <div class="header">
            <h1>📝 My Todos</h1>
            <div>
                <span style="color: #667eea; font-weight: 600;"><?= htmlspecialchars($_SESSION['username']) ?></span>
                <form method="POST" style="display: inline; margin-left: 10px;">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        </div>
        
        <form method="POST" class="todo-form">
            <input type="hidden" name="action" value="add">
            <input type="text" name="title" placeholder="Tambah todo baru..." required>
            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>

        <div class="todo-list">
            <?php if (empty($todos)): ?>
                <p style="text-align:center;color:#999;padding:20px;">Belum ada todo</p>
            <?php else: ?>
                <?php foreach ($todos as $todo): ?>
                    <div class="todo-item <?= $todo['completed'] ? 'completed' : '' ?>">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                            <input type="checkbox" <?= $todo['completed'] ? 'checked' : '' ?> 
                                onchange="this.form.submit()" class="todo-checkbox">
                        </form>
                        <span class="todo-text"><?= htmlspecialchars($todo['title']) ?></span>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                            <button type="submit" class="todo-delete" 
                                onclick="return confirm('Hapus todo ini?')">Hapus</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
