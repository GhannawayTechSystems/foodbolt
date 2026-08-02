<?php

declare(strict_types=1);

/**
 * View rendering helper. Views are plain PHP files under views/ that receive
 * variables via extract(). A shared layout wraps them.
 */

function view(string $template, array $data = [], string $layout = 'layout'): void
{
    echo view_string($template, $data, $layout);
}

function view_string(string $template, array $data = [], string $layout = 'layout'): string
{
    $file = __DIR__ . '/../views/' . $template . '.php';
    if (!is_file($file)) {
        throw new RuntimeException("View not found: {$template}");
    }
    extract($data, EXTR_SKIP);
    ob_start();
    include $file;
    $content = (string) ob_get_clean();

    if ($layout === '') return $content;

    $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';
    if (!is_file($layoutFile)) return $content;

    ob_start();
    include $layoutFile;
    return (string) ob_get_clean();
}

function partial(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);
    include __DIR__ . '/../views/' . $template . '.php';
}
