<?php

namespace Core;

use App\Core\Session;
use App\Managers\AdminManager;
use App\Managers\CommentManager;
use App\Managers\PostManager;
use App\Managers\UserManager;
use App\Service\Auth;
use App\Service\CommentAdministrator;
use App\Service\PostAdministrator;
use App\Service\UserAdministrator;
use Exception;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

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
     * @var PostManager
     */
    protected PostManager $postManager;
    /**
     * @var CommentManager
     */
    protected CommentManager $commentManager;
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
     * @var CommentAdministrator
     */
    protected CommentAdministrator $commentAdministrator;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $config = $this->getConfig();
        $this->twig = $this->initTwig($config);
        $this->setConfig($this->twig, $config);
        $this->twig->addFunction(new TwigFunction('get_session_messages', [$this, 'getMessages']));
        $this->userManager = new UserManager();
        $this->adminManager = new AdminManager();
        $this->postManager = new PostManager();
        $this->commentManager = new CommentManager();
        $this->session = new Session($_SESSION);
        $this->auth = new Auth($this->session);
        $this->twig->addGlobal('session', $this->session->getSession());
        $this->userAdministrator = new UserAdministrator();
        $this->postAdministrator = new PostAdministrator($this->session);
        $this->commentAdministrator = new CommentAdministrator($this->session);
    }

    /**
     * Method used by Twig to display session messages.
     * This method is added to Twig as TwigFunction in Controller constructor.
     *
     * @return null|array
     */
    public function getMessages(): ?array
    {
        return $this->session->getMessages();
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
     * Method to add messages in session.
     *
     * @param array|string $message
     */
    protected function addMessage($message): void
    {
        $this->session->addMessages($message);
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
