<?php

namespace Core;

use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{
    /**
     * @var FilesystemLoader
     */
    protected $loader;
    /**
     * @var Environment
     */
    protected $twig;

    public function __construct()
    {
        $this->loader = new FilesystemLoader('templates', getcwd().'/../');
        $this->twig = new Environment($this->loader);
    }

    /**
     * @throws Exception
     */
    protected function render(string $templateName, array $params = []): void
    {
        try {
            echo $this->twig->render($templateName, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
