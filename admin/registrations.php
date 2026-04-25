<?php

declare(strict_types=1);

require __DIR__ . '/auth.php';
require __DIR__ . '/../db.php';
require __DIR__ . '/../lib/helpers.php';

$courseId = (int) ($_GET['course_id'] ?? 0);
$courseStmt = $pdo->prepare('SELECT * FROM courses WHERE id = :id');
$courseStmt->execute(['id' => $courseId]);
$course = $courseStmt->fetch();

if (!$course) {
    exit('Curso não encontrado.');
}

$regStmt = $pdo->prepare('SELECT * FROM registrations WHERE course_id = :course_id ORDER BY created_at DESC');
$regStmt->execute(['course_id' => $courseId]);
$registrations = $regStmt->fetchAll();

$extraFieldsStmt = $pdo->prepare('SELECT id, label FROM course_extra_fields WHERE course_id = :course_id ORDER BY sort_order ASC, id ASC');
$extraFieldsStmt->execute(['course_id' => $courseId]);
$extraFields = $extraFieldsStmt->fetchAll();

$pageTitle = 'Inscritos';
require __DIR__ . '/_layout_top.php';
?>
<div class="bg-white rounded-xl shadow p-4">
    <h1 class="text-xl font-bold mb-4">Inscritos - <?= e($course['title']) ?></h1>

    <div class="overflow-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b text-left">
                    <th class="p-2">Data</th>
                    <th class="p-2">Nome</th>
                    <th class="p-2">Nascimento</th>
                    <th class="p-2">Sexo</th>
                    <th class="p-2">RG/CPF</th>
                    <th class="p-2">Contato</th>
                    <th class="p-2">Formação</th>
                    <th class="p-2">Endereço</th>
                    <th class="p-2">Camisa</th>
                    <th class="p-2">Pagamento</th>
                    <th class="p-2">Foto</th>
                    <?php foreach ($extraFields as $ef): ?>
                        <th class="p-2"><?= e($ef['label']) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($registrations as $reg): ?>
                <?php
                    $valuesStmt = $pdo->prepare('SELECT field_id, value_text FROM registration_extra_values WHERE registration_id = :registration_id');
                    $valuesStmt->execute(['registration_id' => $reg['id']]);
                    $map = [];
                    foreach ($valuesStmt->fetchAll() as $row) {
                        $map[(int) $row['field_id']] = $row['value_text'];
                    }
                ?>
                <tr class="border-b align-top">
                    <td class="p-2"><?= e($reg['created_at']) ?></td>
                    <td class="p-2"><?= e($reg['full_name']) ?></td>
                    <td class="p-2"><?= e($reg['birth_date']) ?></td>
                    <td class="p-2"><?= e($reg['gender']) ?></td>
                    <td class="p-2"><?= e($reg['rg']) ?><br><?= e($reg['cpf']) ?></td>
                    <td class="p-2"><?= e($reg['whatsapp']) ?></td>
                    <td class="p-2"><?= e($reg['education']) ?></td>
                    <td class="p-2"><?= e($reg['address']) ?><br><?= e($reg['city']) ?></td>
                    <td class="p-2"><?= e($reg['shirt_size']) ?></td>
                    <td class="p-2"><?= e($reg['payment_method']) ?></td>
                    <td class="p-2">
                        <?php if ($reg['photo_path']): ?>
                            <a class="text-blue-600 underline" target="_blank" href="../<?= e($reg['photo_path']) ?>">Ver</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <?php foreach ($extraFields as $ef): ?>
                        <td class="p-2"><?= e($map[(int) $ef['id']] ?? '-') ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/_layout_bottom.php'; ?>
