<?php

namespace Core;

use App\Core\Session;
use App\Exceptions\TwigException;
use App\Managers\SocialNetworkManager;
use App\Service\Auth;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
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
        $this->twig->addFunction(new TwigFunction('get_session_messages', [$this, 'getMessages']));
        $this->twig->addFunction(new TwigFunction('get_social_networks', [$this, 'getSocialNetWorks']));
        $this->session = new Session($_SESSION);
        $this->auth = new Auth($this->session);
        $this->twig->addGlobal('session', $this->session->getSession());
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
     * @throws TwigException
     */
    public function render(string $templateName, array $params = []): void
    {
        try {
            echo $this->twig->render($templateName, $params);
        } catch (LoaderError $e) {
            throw new TwigException(TwigException::LOADER_ERROR_MESSAGE.$e->getMessage());
        } catch (SyntaxError $e) {
            throw new TwigException(TwigException::SYNTAX_ERROR_MESSAGE.$e->getMessage());
        } catch (RuntimeError $e) {
            throw new TwigException(TwigException::RUNTIME_ERROR_MESSAGE.$e->getMessage());
        }
    }

    /**
     * Method used by Twig to display social networks in footer.
     * This method is added to Twig as TwigFunction in Controller constructor.
     *
     * @return array
     */
    public function getSocialNetWorks(): array
    {
        return (new SocialNetworkManager())->findAll();
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
