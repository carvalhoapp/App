<?php

declare(strict_types=1);

session_start();

$config = require __DIR__ . '/../config.php';
require __DIR__ . '/../lib/helpers.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = (string) ($_POST['username'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if ($username === $config['admin']['username'] && $password === $config['admin']['password']) {
        $_SESSION['admin_logged'] = true;
        redirect('index.php');
    }

    $error = 'Usuário ou senha inválidos.';
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Painel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <form method="post" class="bg-white w-full max-w-md rounded-xl shadow p-6 space-y-4">
        <h1 class="text-2xl font-bold">Painel de Cursos</h1>
        <?php if ($error): ?><div class="bg-red-50 text-red-600 p-2 rounded"><?= e($error) ?></div><?php endif; ?>
        <div><label class="block text-sm">Usuário</label><input name="username" class="w-full border rounded px-3 py-2" required></div>
        <div><label class="block text-sm">Senha</label><input type="password" name="password" class="w-full border rounded px-3 py-2" required></div>
        <button class="w-full bg-slate-900 text-white py-2 rounded">Entrar</button>
        <p class="text-xs text-slate-500">Padrão: admin / admin123</p>
    </form>
</body>
</html>
