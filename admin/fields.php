<?php

declare(strict_types=1);

require __DIR__ . '/auth.php';
require __DIR__ . '/../db.php';
require __DIR__ . '/../lib/helpers.php';

$courseId = (int) ($_GET['course_id'] ?? $_POST['course_id'] ?? 0);
$courseStmt = $pdo->prepare('SELECT * FROM courses WHERE id = :id');
$courseStmt->execute(['id' => $courseId]);
$course = $courseStmt->fetch();

if (!$course) {
    exit('Curso não encontrado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $del = $pdo->prepare('DELETE FROM course_extra_fields WHERE id = :id AND course_id = :course_id');
        $del->execute(['id' => (int) $_POST['delete_id'], 'course_id' => $courseId]);
    } else {
        $label = trim((string) ($_POST['label'] ?? ''));
        $fieldName = strtolower(preg_replace('/[^a-zA-Z0-9_]+/', '_', $label) ?? 'campo');
        $ins = $pdo->prepare('INSERT INTO course_extra_fields (course_id, label, field_name, field_type, options_text, is_required, sort_order) VALUES (:course_id, :label, :field_name, :field_type, :options_text, :is_required, :sort_order)');
        $ins->execute([
            'course_id' => $courseId,
            'label' => $label,
            'field_name' => $fieldName,
            'field_type' => (string) ($_POST['field_type'] ?? 'text'),
            'options_text' => trim((string) ($_POST['options_text'] ?? '')),
            'is_required' => isset($_POST['is_required']) ? 1 : 0,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
        ]);
    }
    redirect(baseUrl('admin/fields.php?course_id=' . $courseId));
}

$fieldsStmt = $pdo->prepare('SELECT * FROM course_extra_fields WHERE course_id = :course_id ORDER BY sort_order ASC, id ASC');
$fieldsStmt->execute(['course_id' => $courseId]);
$fields = $fieldsStmt->fetchAll();

$pageTitle = 'Inputs extras';
require __DIR__ . '/_layout_top.php';
?>
<div class="grid md:grid-cols-2 gap-4">
    <div class="bg-white rounded-xl shadow p-4">
        <h1 class="text-lg font-bold">Novo input extra - <?= e($course['title']) ?></h1>
        <form method="post" class="space-y-3 mt-3">
            <input type="hidden" name="course_id" value="<?= (int) $courseId ?>">
            <div><label class="text-sm block">Rótulo</label><input required name="label" class="w-full border rounded px-3 py-2"></div>
            <div><label class="text-sm block">Tipo</label>
                <select name="field_type" class="w-full border rounded px-3 py-2">
                    <option value="text">Texto</option>
                    <option value="number">Número</option>
                    <option value="date">Data</option>
                    <option value="select">Seleção</option>
                    <option value="textarea">Texto longo</option>
                </select>
            </div>
            <div><label class="text-sm block">Opções (apenas select, separadas por vírgula)</label><input name="options_text" class="w-full border rounded px-3 py-2"></div>
            <div><label class="text-sm block">Ordem</label><input type="number" name="sort_order" value="0" class="w-full border rounded px-3 py-2"></div>
            <label class="text-sm flex gap-2"><input type="checkbox" name="is_required">Obrigatório</label>
            <button class="bg-emerald-600 text-white px-4 py-2 rounded">Adicionar campo</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow p-4">
        <h2 class="text-lg font-bold mb-2">Campos cadastrados</h2>
        <?php if (!$fields): ?>
            <p class="text-sm text-slate-500">Nenhum campo extra.</p>
        <?php endif; ?>
        <div class="space-y-2">
            <?php foreach ($fields as $field): ?>
                <div class="border rounded p-2 flex justify-between items-center">
                    <div>
                        <div class="font-medium"><?= e($field['label']) ?></div>
                        <div class="text-xs text-slate-500">Tipo: <?= e($field['field_type']) ?> | Obrigatório: <?= $field['is_required'] ? 'Sim' : 'Não' ?></div>
                    </div>
                    <form method="post" onsubmit="return confirm('Excluir campo?')">
                        <input type="hidden" name="course_id" value="<?= (int) $courseId ?>">
                        <input type="hidden" name="delete_id" value="<?= (int) $field['id'] ?>">
                        <button class="text-xs bg-red-600 text-white px-2 py-1 rounded">Excluir</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php require __DIR__ . '/_layout_bottom.php'; ?>
