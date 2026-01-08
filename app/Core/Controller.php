<?php

declare(strict_types=1);

namespace Mini\Core;

class Controller
{

    protected function render(string $view, array $params = []): void
    {

        extract(array: $params);

        $viewFile = dirname(__DIR__) . '/Views/' . $view . '.php';

        $layoutFile = dirname(__DIR__) . '/Views/layout.php';


        ob_start();

        require $viewFile;


        $content = ob_get_clean();


        require $layoutFile;
    }
}


