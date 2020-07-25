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
        $this->setConfig($this->twig);
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

    private function setConfig(Environment $twig): void
    {
        $configFile = file_get_contents(__DIR__.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'config'.\DIRECTORY_SEPARATOR.'config.yaml');

        $parsedConfigFile = yaml_parse($configFile);

        foreach ($parsedConfigFile as $key => $config) {
            if (\in_array(strtolower($key), ['locale', 'charset'], true)) {
                $twig->addGlobal(strtolower($key), strtolower($config));
            }
        }
    }
}
