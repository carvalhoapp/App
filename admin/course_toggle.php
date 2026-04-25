<?php

declare(strict_types=1);

require __DIR__ . '/auth.php';
require __DIR__ . '/../db.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('UPDATE courses SET is_active = IF(is_active = 1, 0, 1) WHERE id = :id');
$stmt->execute(['id' => $id]);

header('Location: index.php');
exit;
