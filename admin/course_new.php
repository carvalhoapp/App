<?php

declare(strict_types=1);

require __DIR__ . '/auth.php';
require __DIR__ . '/../db.php';
require __DIR__ . '/../lib/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO courses (title, city, description, price, is_active) VALUES (:title, :city, :description, :price, 1)');
    $stmt->execute([
        'title' => trim((string) ($_POST['title'] ?? '')),
        'city' => trim((string) ($_POST['city'] ?? '')),
        'description' => trim((string) ($_POST['description'] ?? '')),
        'price' => (float) ($_POST['price'] ?? 0),
    ]);
    redirect('index.php');
}

$pageTitle = 'Novo curso';
require __DIR__ . '/_layout_top.php';
?>
<div class="bg-white rounded-xl shadow p-4 max-w-2xl">
    <h1 class="text-xl font-bold mb-4">Novo curso</h1>
    <form method="post" class="space-y-3">
        <div><label class="text-sm block">Título</label><input required name="title" class="w-full border rounded px-3 py-2"></div>
        <div><label class="text-sm block">Cidade</label><input required name="city" class="w-full border rounded px-3 py-2"></div>
        <div><label class="text-sm block">Descrição</label><textarea name="description" class="w-full border rounded px-3 py-2"></textarea></div>
        <div><label class="text-sm block">Valor</label><input type="number" step="0.01" min="0" required name="price" class="w-full border rounded px-3 py-2"></div>
        <button class="bg-emerald-600 text-white px-4 py-2 rounded">Salvar curso</button>
    </form>
</div>
<?php require __DIR__ . '/_layout_bottom.php'; ?>
