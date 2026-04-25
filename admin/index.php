<?php

declare(strict_types=1);

require __DIR__ . '/auth.php';
require __DIR__ . '/../db.php';
require __DIR__ . '/../lib/helpers.php';

$courses = $pdo->query('SELECT * FROM courses ORDER BY created_at DESC')->fetchAll();
$pageTitle = 'Cursos';
require __DIR__ . '/_layout_top.php';
?>
<div class="bg-white rounded-xl shadow p-4">
    <h1 class="text-xl font-bold mb-4">Cursos cadastrados</h1>
    <div class="overflow-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-left">
                    <th class="p-2">Título</th>
                    <th class="p-2">Cidade</th>
                    <th class="p-2">Valor</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($courses as $course): ?>
                <tr class="border-b">
                    <td class="p-2"><?= e($course['title']) ?></td>
                    <td class="p-2"><?= e($course['city']) ?></td>
                    <td class="p-2"><?= e(formatCurrency((float) $course['price'])) ?></td>
                    <td class="p-2"><?= $course['is_active'] ? 'Ativo' : 'Inativo' ?></td>
                    <td class="p-2 flex flex-wrap gap-2">
                        <a class="px-2 py-1 bg-blue-600 text-white rounded" href="course_edit.php?id=<?= (int) $course['id'] ?>">Editar</a>
                        <a class="px-2 py-1 bg-violet-600 text-white rounded" href="fields.php?course_id=<?= (int) $course['id'] ?>">Inputs extras</a>
                        <a class="px-2 py-1 bg-emerald-600 text-white rounded" href="registrations.php?course_id=<?= (int) $course['id'] ?>">Inscritos</a>
                        <a class="px-2 py-1 bg-amber-600 text-white rounded" href="course_toggle.php?id=<?= (int) $course['id'] ?>">Ativar/Desativar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/_layout_bottom.php'; ?>
