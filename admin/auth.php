<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/../lib/helpers.php';

if (empty($_SESSION['admin_logged'])) {
    redirect(baseUrl('admin/login.php'));
}
