<?php

declare(strict_types=1);

require __DIR__ . '/auth.php';
require __DIR__ . '/../db.php';
require __DIR__ . '/../lib/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $up = $pdo->prepare('UPDATE settings SET whatsapp_number = :whatsapp WHERE id = 1');
    $up->execute(['whatsapp' => trim((string) ($_POST['whatsapp_number'] ?? ''))]);
    redirect('settings.php?saved=1');
}

$settings = $pdo->query('SELECT * FROM settings WHERE id = 1')->fetch();

$pageTitle = 'Configurações';
require __DIR__ . '/_layout_top.php';
?>
<div class="bg-white rounded-xl shadow p-4 max-w-xl">
    <h1 class="text-xl font-bold mb-4">Configurações</h1>
    <?php if (isset($_GET['saved'])): ?><div class="bg-emerald-100 text-emerald-700 p-2 rounded mb-3">Configuração salva!</div><?php endif; ?>
    <form method="post" class="space-y-3">
        <div>
            <label class="text-sm block">WhatsApp de atendimento (com DDI)</label>
            <input name="whatsapp_number" required class="w-full border rounded px-3 py-2" value="<?= e($settings['whatsapp_number'] ?? '') ?>">
            <p class="text-xs text-slate-500 mt-1">Ex: 5511999998888</p>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Salvar</button>
    </form>
</div>
<?php require __DIR__ . '/_layout_bottom.php'; ?>
