<?php

namespace App\Core\Service;

use App\Core\Session;
use App\Core\Validation\ValidatorFactory;
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
     * @throws Exception
     *
     * @return mixed|string[]
     */
    public function authenticateUser(array $data)
    {
        $validator = ValidatorFactory::create(ValidatorFactory::LOGIN, $data, $this->userManager);

        if (!$validator->isValid()) {
            return $validator->getErrors();
        }

        $user = $this->userManager->findOneBy(['email' => $data['email']]);

        if (empty($user)) {
            throw new Exception('Aucun utilisateur trouvé pour l\'adresse email : '.$data['email']);
        }

        if (false === $this->checkPassword($data['password'], $user['password'])) {
            throw new Exception('Mot de passe invalide, veuillez réessayer.');
        }

        return $user['id'];
    }

    /**
     * @return null|mixed
     */
    public function getCurrentUserRole()
    {
        return $this->session->get('user')['base_infos']['role'];
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
