<?php

declare(strict_types=1);

session_start();
require __DIR__ . '/../lib/helpers.php';

session_destroy();
redirect(baseUrl('admin/login.php'));
