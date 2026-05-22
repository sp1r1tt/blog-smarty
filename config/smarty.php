<?php

declare(strict_types=1);

// Конфиг Smarty: пути к шаблонам/кэшу/компиляции и настройка автоэкранирования HTML.

$root = dirname(__DIR__);

return [
    'template_dir' => $root . '/templates',
    'compile_dir' => $root . '/templates_c',
    'config_dir' => $root . '/configs',
    'cache_dir' => $root . '/cache',
    'escape_html' => true,
];

