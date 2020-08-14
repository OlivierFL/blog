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

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->loader = new FilesystemLoader('templates', getcwd().'/../');
        $this->twig = new Environment($this->loader);
        $this->setConfig($this->twig);
    }

    /**
     * @param string $templateName
     * @param array  $params
     *
     * @throws Exception
     */
    protected function render(string $templateName, array $params = []): void
    {
        try {
            echo $this->twig->render($templateName, $params);
        } catch (Exception $e) {
            throw new Exception('Erreur lors du rendu du template : '.$e->getMessage());
        }
    }

    /**
     * @param Environment $twig
     */
    private function setConfig(Environment $twig): void
    {
        $parsedConfigFile = yaml_parse_file(__DIR__.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'config'.\DIRECTORY_SEPARATOR.'config.yaml');

        foreach ($parsedConfigFile as $key => $config) {
            if (\in_array(strtolower($key), ['locale', 'charset'], true)) {
                $twig->addGlobal(strtolower($key), strtolower($config));
            }
        }
    }
}
