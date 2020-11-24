<?php

namespace App\Service;

use App\Core\Session;
use App\Core\Validation\Validator;
use App\Exceptions\InvalidPasswordException;
use App\Exceptions\UserNotFoundException;
use App\Managers\UserManager;
use Exception;

class Auth
{
    /**
     * @var UserManager
     */
    private UserManager $userManager;
    /**
     * @var Session
     */
    private Session $session;

    /**
     * Auth constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->userManager = new UserManager();
        $this->session = $session;
    }

    /**
     * @param array $data
     *
     * @throws InvalidPasswordException
     * @throws UserNotFoundException
     * @throws Exception
     *
     * @return string[]|void
     */
    public function authenticateUser(array $data)
    {
        $validator = (new Validator($data, $this->userManager))->getBaseValidator();

        if (!$validator->isValid()) {
            return $validator->getErrors();
        }

        $user = $this->userManager->findOneBy(['email' => $data['email']]);

        if (empty($user) || 'ROLE_DISABLED' === $user->getRole()) {
            throw new UserNotFoundException('Aucun utilisateur trouvé pour l\'adresse email : '.$data['email']);
        }

        if (false === $this->checkPassword($data['password'], $user->getPassword())) {
            throw new InvalidPasswordException('Mot de passe invalide, veuillez réessayer.');
        }

        $authenticatedUser = $this->userManager->findUser($user->getId());
        $this->session->set('current_user', $authenticatedUser);
        $this->session->addMessages('Connexion réussie');
    }

    /**
     * @return null|mixed
     */
    public function getCurrentUserRole()
    {
        return $this->session->get('current_user')->getRole();
    }

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    private function checkPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
