<?php

declare(strict_types=1);

// Обертка над Smarty: создание инстанса по конфигу и рендер шаблонов с переменными.

namespace App\Core;

use Smarty\Smarty;

class View
{
    public function __construct(private Smarty $smarty)
    {
    }

    public static function make(array $smartyConfig): self
    {
        $smarty = new Smarty();
        $smarty->setTemplateDir($smartyConfig['template_dir']);
        $smarty->setCompileDir($smartyConfig['compile_dir']);
        $smarty->setConfigDir($smartyConfig['config_dir']);
        $smarty->setCacheDir($smartyConfig['cache_dir']);
        $smarty->setEscapeHtml((bool) ($smartyConfig['escape_html'] ?? false));

        return new self($smarty);
    }

    public function render(string $template, array $vars = []): void
    {
        foreach ($vars as $name => $value) {
            $this->smarty->assign($name, $value);
        }

        $this->smarty->display($template);
    }

    public function testInstall(): void
    {
        $this->smarty->testInstall();
    }
}
