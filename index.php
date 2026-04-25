<?php

declare(strict_types=1);

require __DIR__ . '/db.php';
require __DIR__ . '/lib/helpers.php';

$stmt = $pdo->query('SELECT * FROM courses WHERE is_active = 1 ORDER BY created_at DESC');
$courses = $stmt->fetchAll();
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cursos abertos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">
<div class="max-w-5xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-slate-800">Inscrições em Cursos</h1>
        <a href="admin/login.php" class="text-sm bg-slate-900 text-white px-4 py-2 rounded-lg">Painel</a>
    </div>

    <?php if (!$courses): ?>
        <div class="bg-white rounded-xl p-6 shadow">Nenhum curso ativo no momento.</div>
    <?php else: ?>
        <div class="grid md:grid-cols-2 gap-4">
            <?php foreach ($courses as $course): ?>
                <div class="bg-white rounded-xl shadow p-5 border border-slate-200">
                    <h2 class="text-xl font-semibold text-slate-900"><?= e($course['title']) ?></h2>
                    <p class="text-sm text-slate-600 mt-1">Cidade: <?= e($course['city']) ?></p>
                    <p class="text-sm text-slate-600">Valor: <?= e(formatCurrency((float) $course['price'])) ?></p>
                    <p class="mt-3 text-slate-700 text-sm"><?= nl2br(e($course['description'])) ?></p>
                    <a href="curso.php?id=<?= (int) $course['id'] ?>" class="inline-block mt-4 bg-emerald-600 text-white px-4 py-2 rounded-lg">Inscrever-se</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
