<?php

declare(strict_types=1);

require __DIR__ . '/db.php';
require __DIR__ . '/lib/helpers.php';

$courseId = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM courses WHERE id = :id AND is_active = 1');
$stmt->execute(['id' => $courseId]);
$course = $stmt->fetch();

if (!$course) {
    http_response_code(404);
    echo 'Curso não encontrado ou inativo.';
    exit;
}

$fieldStmt = $pdo->prepare('SELECT * FROM course_extra_fields WHERE course_id = :id ORDER BY sort_order ASC, id ASC');
$fieldStmt->execute(['id' => $courseId]);
$extraFields = $fieldStmt->fetchAll();
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulário - <?= e($course['title']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" integrity="sha512-J2L5fY4yQ5L2DK3lMJx4PSJVWf3sR4YgAajrg4fCrb2SljPjUNQwM1icdX5Cgh4nP8B3N6A2M6lt1YwzE8n7ig==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-slate-100">
<div class="max-w-3xl mx-auto p-6">
    <a href="index.php" class="text-sm text-slate-600">← Voltar</a>
    <div class="bg-white rounded-xl shadow p-6 mt-3">
        <h1 class="text-2xl font-bold"><?= e($course['title']) ?></h1>
        <p class="text-sm text-slate-600">Cidade: <?= e($course['city']) ?> | Valor: <?= e(formatCurrency((float) $course['price'])) ?></p>
        <p class="mt-3 text-slate-700"><?= nl2br(e($course['description'])) ?></p>

        <form class="mt-6 space-y-4" method="post" action="submit.php">
            <input type="hidden" name="course_id" value="<?= (int) $course['id'] ?>">
            <input type="hidden" name="cropped_photo" id="cropped_photo">

            <div><label class="block text-sm">Nome</label><input required name="full_name" class="w-full border rounded-lg px-3 py-2"></div>
            <div><label class="block text-sm">Data de Nascimento</label><input required type="date" name="birth_date" class="w-full border rounded-lg px-3 py-2"></div>
            <div><label class="block text-sm">Sexo</label><select required name="gender" class="w-full border rounded-lg px-3 py-2"><option value="">Selecione</option><option>Masculino</option><option>Feminino</option><option>Outro</option></select></div>
            <div><label class="block text-sm">RG</label><input required name="rg" class="w-full border rounded-lg px-3 py-2"></div>
            <div><label class="block text-sm">CPF</label><input required name="cpf" class="w-full border rounded-lg px-3 py-2"></div>
            <div><label class="block text-sm">WhatsApp</label><input required name="whatsapp" class="w-full border rounded-lg px-3 py-2"></div>
            <div><label class="block text-sm">Formação</label><input required name="education" class="w-full border rounded-lg px-3 py-2"></div>
            <div><label class="block text-sm">Endereço</label><input required name="address" class="w-full border rounded-lg px-3 py-2"></div>
            <div><label class="block text-sm">Cidade</label><input required name="city" class="w-full border rounded-lg px-3 py-2"></div>

            <div>
                <label class="block text-sm">Tamanho da camisa</label>
                <select required name="shirt_size" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Selecione</option>
                    <option>P</option><option>M</option><option>G</option><option>GG</option>
                </select>
            </div>

            <div>
                <label class="block text-sm">Foto 3x4 (com corte)</label>
                <input type="file" id="photo_input" accept="image/*" class="w-full border rounded-lg px-3 py-2">
                <div class="mt-3"><img id="photo_preview" class="max-h-72 rounded border" alt="Prévia da foto"></div>
            </div>

            <?php foreach ($extraFields as $field): ?>
                <?php $name = 'extra_' . (int) $field['id']; ?>
                <div>
                    <label class="block text-sm"><?= e($field['label']) ?></label>
                    <?php if ($field['field_type'] === 'textarea'): ?>
                        <textarea name="<?= e($name) ?>" class="w-full border rounded-lg px-3 py-2" <?= $field['is_required'] ? 'required' : '' ?>></textarea>
                    <?php elseif ($field['field_type'] === 'select'): ?>
                        <select name="<?= e($name) ?>" class="w-full border rounded-lg px-3 py-2" <?= $field['is_required'] ? 'required' : '' ?>>
                            <option value="">Selecione</option>
                            <?php foreach (array_filter(array_map('trim', explode(',', (string) $field['options_text']))) as $opt): ?>
                                <option><?= e($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input type="<?= e($field['field_type']) ?>" name="<?= e($name) ?>" class="w-full border rounded-lg px-3 py-2" <?= $field['is_required'] ? 'required' : '' ?>>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <div>
                <label class="block text-sm">Forma de pagamento</label>
                <select required name="payment_method" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Selecione</option>
                    <option value="pix">Pix</option>
                    <option value="boleto">Boleto</option>
                    <option value="cartao">Cartão</option>
                    <option value="dinheiro">Dinheiro</option>
                </select>
            </div>

            <label class="flex items-start gap-2 text-sm">
                <input type="checkbox" required name="accepted_terms" value="1" class="mt-1">
                Declaro que as informações são verdadeiras e aceito os termos de inscrição.
            </label>

            <button class="w-full bg-emerald-600 text-white py-3 rounded-lg font-semibold" type="submit">Finalizar inscrição</button>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js" integrity="sha512-EuY5Q4VfB3fYfyhNIl5Z8oV3mW4+5P5eyxVgtZoSmw1t+8TMzZYw3e9D0MqDjcg1FlRnf6x5Q8E8h4nYf0bL3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
const input = document.getElementById('photo_input');
const preview = document.getElementById('photo_preview');
const hidden = document.getElementById('cropped_photo');
let cropper = null;

input.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = () => {
        preview.src = reader.result;
        if (cropper) cropper.destroy();
        cropper = new Cropper(preview, {
            aspectRatio: 3 / 4,
            viewMode: 1,
            autoCropArea: 1,
            crop() {
                const canvas = cropper.getCroppedCanvas({ width: 300, height: 400 });
                hidden.value = canvas.toDataURL('image/jpeg', 0.9);
            }
        });
    };
    reader.readAsDataURL(file);
});
</script>
</body>
</html>
