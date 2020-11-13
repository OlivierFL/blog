<?php

namespace App\Controller;

use App\Core\Validation\Validator;
use App\Exceptions\InvalidMethodException;
use App\Exceptions\TwigException;
use App\Managers\PostManager;
use App\Service\Mailer;
use App\Managers\UserManager;
use App\Service\UserAdministrator;
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
     * @var UserManager
     */
    private UserManager $userManager;

    /**
     * IndexController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
        $this->postManager = new PostManager();
        $this->userAdministrator = new UserAdministrator($this->session);
    }

    /**
     * @throws TwigException
     * @throws Exception
     */
    public function index(): void
    {
        $this->render('layout/index.html.twig', [
            'user' => $this->userManager->findUser(33),
            'posts' => $this->postManager->findAllWithAuthor(3),
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
    public function sendEmail(): void
    {
        $validator = (new Validator($_POST))->getContactValidator();

        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            if ($validator->isValid()) {
                (new Mailer($this->session))->sendEmail($_POST);
            } else {
                $this->session->addMessages($validator->getErrors());
            }

            header('Location: /');
        }

        throw InvalidMethodException::methodNotAllowed($_SERVER['REQUEST_METHOD']);
    }
}
