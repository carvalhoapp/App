<?php

declare(strict_types=1);

require __DIR__ . '/auth.php';
require __DIR__ . '/../db.php';
require __DIR__ . '/../lib/helpers.php';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM courses WHERE id = :id');
$stmt->execute(['id' => $id]);
$course = $stmt->fetch();

if (!$course) {
    exit('Curso não encontrado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upd = $pdo->prepare('UPDATE courses SET title = :title, city = :city, description = :description, price = :price WHERE id = :id');
    $upd->execute([
        'id' => $id,
        'title' => trim((string) ($_POST['title'] ?? '')),
        'city' => trim((string) ($_POST['city'] ?? '')),
        'description' => trim((string) ($_POST['description'] ?? '')),
        'price' => (float) ($_POST['price'] ?? 0),
    ]);
    redirect('index.php');
}

$pageTitle = 'Editar curso';
require __DIR__ . '/_layout_top.php';
?>
<div class="bg-white rounded-xl shadow p-4 max-w-2xl">
    <h1 class="text-xl font-bold mb-4">Editar curso</h1>
    <form method="post" class="space-y-3">
        <input type="hidden" name="id" value="<?= (int) $course['id'] ?>">
        <div><label class="text-sm block">Título</label><input required name="title" value="<?= e($course['title']) ?>" class="w-full border rounded px-3 py-2"></div>
        <div><label class="text-sm block">Cidade</label><input required name="city" value="<?= e($course['city']) ?>" class="w-full border rounded px-3 py-2"></div>
        <div><label class="text-sm block">Descrição</label><textarea name="description" class="w-full border rounded px-3 py-2"><?= e($course['description']) ?></textarea></div>
        <div><label class="text-sm block">Valor</label><input type="number" step="0.01" min="0" required name="price" value="<?= e((string) $course['price']) ?>" class="w-full border rounded px-3 py-2"></div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Atualizar</button>
    </form>
</div>
<?php require __DIR__ . '/_layout_bottom.php'; ?>
