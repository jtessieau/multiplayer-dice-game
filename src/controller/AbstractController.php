<?php

namespace App\Controller;

class AbstractController
{
    public function render(string $title, string $path, array $params = []): void
    {
        if (!empty($params)) {
            extract($params);
        }
        ob_start();
        require_once '../template/' . $path;

        $content = ob_get_clean();
        require_once '../template/base.html.php';
    }


}

