<?php

declare(strict_types=1);
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?= isset($pageTitle) ? e($pageTitle) : 'Painel' ?></title>
</head>
<body class="bg-slate-100 min-h-screen">
<div class="max-w-6xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow mb-4 p-4 flex flex-wrap gap-2 justify-between items-center">
        <div class="font-bold text-lg">Painel de Cursos</div>
        <div class="flex flex-wrap gap-2 text-sm">
            <a class="px-3 py-2 bg-slate-800 text-white rounded" href="index.php">Cursos</a>
            <a class="px-3 py-2 bg-slate-200 rounded" href="course_new.php">Novo curso</a>
            <a class="px-3 py-2 bg-slate-200 rounded" href="settings.php">Configurações</a>
            <a class="px-3 py-2 bg-red-500 text-white rounded" href="logout.php">Sair</a>
        </div>
    </div>
