<?php

namespace App\Controller;

use App\Exceptions\TwigException;
use App\Managers\PostManager;
use App\Service\UserAdministrator;
use App\Core\Validation\Validator;
use App\Service\Mailer;
use Core\Controller;
use Exception;

class IndexController extends Controller
{
    /**
     * @var PostManager
     */
    protected PostManager $postManager;
    /**
     * @var UserAdministrator
     */
    protected UserAdministrator $userAdministrator;

    /**
     * IndexController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->postManager = new PostManager();
        $this->userAdministrator = new UserAdministrator($this->session);
    }

    /**
     * @throws TwigException
     * @throws Exception
     */
    public function index(): void
    {
        $admin = $this->userAdministrator->getUser(33);
        $posts = $this->postManager->findAllWithAuthor(3);

        $this->render('layout/index.html.twig', [
            'admin' => $admin,
            'posts' => $posts,
        ]);
    }

    /**
     * @throws TwigException
     */
    public function notFound(): void
    {
        $this->render('layout/errors/404.html.twig');
    }

    /**
     * @throws Exception
     */
    public function sendMail(): void
    {
        $validator = (new Validator($_POST))->getContactValidator();

        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST) && $validator->isValid()) {
            $result = (new Mailer())->send($_POST);
            $this->addMessage($result);
            header('Location: /');
        }

        throw new Exception('Erreur lors de l\'envoi de l\'email');
    }
}
