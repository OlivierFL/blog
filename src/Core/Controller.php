<?php

namespace Core;

use App\Core\Service\Auth;
use App\Core\Service\PostAdministrator;
use App\Core\Service\UserAdministrator;
use App\Core\Session;
use App\Managers\AdminManager;
use App\Managers\UserManager;
use Exception;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class Controller
{
    /**
     * @var FilesystemLoader
     */
    protected FilesystemLoader $loader;
    /**
     * @var Environment
     */
    protected Environment $twig;
    /**
     * @var UserManager
     */
    protected UserManager $userManager;
    /**
     * @var AdminManager
     */
    protected AdminManager $adminManager;
    /**
     * @var UserAdministrator
     */
    protected UserAdministrator $userAdministrator;
    /**
     * @var PostAdministrator
     */
    protected PostAdministrator $postAdministrator;
    /**
     * @var Session
     */
    protected Session $session;
    /**
     * @var Auth
     */
    protected Auth $auth;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $config = $this->getConfig();
        $this->twig = $this->initTwig($config);
        $this->setConfig($this->twig, $config);
        $this->userManager = new UserManager();
        $this->adminManager = new AdminManager();
        $this->session = new Session($_SESSION);
        $this->auth = new Auth($this->session);
        $this->twig->addGlobal('session', $this->session->getSession());
        $this->userAdministrator = new UserAdministrator();
        $this->postAdministrator = new PostAdministrator($this->session);
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
     * @return mixed
     */
    private function getConfig()
    {
        return yaml_parse_file(__DIR__.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'config'.\DIRECTORY_SEPARATOR.'config.yaml');
    }

    /**
     * @param $config
     *
     * @return Environment
     */
    private function initTwig($config): Environment
    {
        if (\array_key_exists('APP_ENV', $config) && 'dev' === strtolower($config['APP_ENV'])) {
            $this->loader = new FilesystemLoader('templates', getcwd().'/../');
            $this->twig = new Environment($this->loader, ['debug' => true]);
            $this->twig->addExtension(new DebugExtension());

            return $this->twig;
        }

        $this->loader = new FilesystemLoader('templates', getcwd().'/../');
        $this->twig = new Environment($this->loader);

        return $this->twig;
    }

    /**
     * @param Environment $twig
     * @param             $configFile
     */
    private function setConfig(Environment $twig, $configFile): void
    {
        foreach ($configFile as $key => $config) {
            if (\in_array(strtolower($key), ['locale', 'charset'], true)) {
                $twig->addGlobal(strtolower($key), strtolower($config));
            }
        }
    }
}
