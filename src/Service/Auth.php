<?php

namespace App\Service;

use App\Core\Session;
use App\Core\Validation\Validator;
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
     * @var UserAdministrator
     */
    private UserAdministrator $userAdministrator;

    /**
     * Auth constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->userManager = new UserManager();
        $this->userAdministrator = new UserAdministrator();
        $this->session = $session;
    }

    /**
     * @param array $data
     *
     * @throws Exception
     *
     * @return string[]|void
     */
    public function authenticateUser(array $data)
    {
        $validator = (new Validator($data, $this->userManager))->getLoginValidator();

        if (!$validator->isValid()) {
            return $validator->getErrors();
        }

        $user = $this->userManager->findOneBy(['email' => $data['email']]);

        if (empty($user) || 'ROLE_DISABLED' === $user['role']) {
            throw new Exception('Aucun utilisateur trouvé pour l\'adresse email : '.$data['email']);
        }

        if (false === $this->checkPassword($data['password'], $user['password'])) {
            throw new Exception('Mot de passe invalide, veuillez réessayer.');
        }

        $authenticatedUser = $this->userAdministrator->getUser($user['id']);
        $this->session->set('current_user', $authenticatedUser);
    }

    /**
     * @return null|mixed
     */
    public function getCurrentUserRole()
    {
        return $this->session->get('current_user')['base_infos']['role'];
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
