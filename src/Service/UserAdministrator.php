<?php

namespace App\Service;

use App\Core\PDOFactory;
use App\Core\Session;
use App\Core\Validation\Validator;
use App\Exceptions\DatabaseException;
use App\Exceptions\UserException;
use App\Managers\AdminManager;
use App\Managers\UserManager;
use App\Model\Admin;
use App\Model\User;
use Exception;
use PDO;
use ReflectionException;

class UserAdministrator
{
    /**
     * @var UserManager
     */
    private UserManager $userManager;
    /**
     * @var AdminManager
     */
    private AdminManager $adminManager;
    /**
     * @var PDO
     */
    private PDO $db;
    /**
     * @var Session
     */
    private Session $session;

    /**
     * UserAdministrator constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->userManager = new UserManager();
        $this->adminManager = new AdminManager();
        $this->db = (new PDOFactory())->getMysqlConnexion();
        $this->session = $session;
    }

    /**
     * @param array $data
     *
     * @throws Exception
     */
    public function createUser(array $data): void
    {
        $validator = (new Validator($data, $this->userManager))->getSignUpValidator();

        if ($validator->isValid()) {
            $user = new User($data);
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));

            $result = $this->userManager->create($user);

            if (false === $result) {
                throw UserException::create($user->getId());
            }

            $this->session->addMessages('Félicitations ! Vous êtes désormais inscrit et vous pouvez dès à présent poster des commentaires');

            return;
        }

        $this->session->addMessages($validator->getErrors());
    }

    /**
     * @param array $user
     * @param array $data
     *
     * @throws DatabaseException
     * @throws UserException
     * @throws Exception
     * @throws ReflectionException
     */
    public function updateUser(array $user, array $data): void
    {
        $validator = (new Validator($data, $this->userManager))->getUserUpdateValidator();
        $user['base_infos'] = array_merge($user['base_infos'], $data);

        if ($validator->isValid()) {
            $updatedUser = new User($user['base_infos']);

            $result = $this->userManager->update($updatedUser);

            if (false === $result) {
                throw UserException::update($updatedUser->getId());
            }

            $this->session->addMessages('Utilisateur mis à jour');

            return;
        }

        $this->session->addMessages($validator->getErrors());
    }

    /**
     * @param array $user
     *
     * @throws UserException
     * @throws Exception
     */
    public function deleteUser(array $user): void
    {
        $user = $this->anonymizeUser($user);

        $deletedUser = new User($user['base_infos']);
        if (null !== $user['admin_infos']) {
            $deletedAdmin = new Admin($user['admin_infos']);
        }

        try {
            $this->db->beginTransaction();
            $this->userManager->update($deletedUser);
            if (isset($deletedAdmin)) {
                $this->adminManager->update($deletedAdmin);
            }
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();

            throw UserException::delete($deletedUser->getId());
        }

        $this->session->addMessages('Utilisateur supprimé');
    }

    /**
     * @param array $user
     *
     * @throws Exception
     *
     * @return array
     */
    private function anonymizeUser(array $user): array
    {
        $anonymousUser = 'anonymous'.$user['base_infos']['id'];
        $user['base_infos']['user_name'] = $anonymousUser;
        $user['base_infos']['first_name'] = $anonymousUser;
        $user['base_infos']['last_name'] = $anonymousUser;
        $user['base_infos']['email'] = $anonymousUser.'@example.com';
        $user['base_infos']['role'] = 'ROLE_DISABLED';
        $user['base_infos']['updated_at'] = (new \DateTime())->format('Y-m-d H:i:s');

        if (null !== $user['admin_infos']) {
            unset($user['admin_infos']);
        }

        return $user;
    }
}
