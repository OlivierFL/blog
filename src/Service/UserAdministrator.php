<?php

namespace App\Service;

use App\Core\PDOFactory;
use App\Core\Validation\ValidatorFactory;
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
     * UserAdministrator constructor.
     */
    public function __construct()
    {
        $this->userManager = new UserManager();
        $this->adminManager = new AdminManager();
        $this->db = (new PDOFactory())->getMysqlConnexion();
    }

    /**
     * @param int $id
     *
     * @throws Exception
     *
     * @return array
     */
    public function getUser(int $id): array
    {
        $userInfos = $this->userManager->findOneBy(['id' => $id]);

        if ('admin' === $userInfos['role']) {
            $adminInfos = $this->adminManager->findOneBy(['user_id' => $id]);
        }

        return array_combine(['base_infos', 'admin_infos'], [$userInfos, $adminInfos ?? null]);
    }

    /**
     * @param array $data
     *
     * @throws Exception
     *
     * @return array
     */
    public function createUser(array $data): array
    {
        $validator = ValidatorFactory::create(ValidatorFactory::SIGN_UP, $_POST, $this->userManager);

        if ($validator->isValid()) {
            $user = new User($data);
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));

            $result = $this->userManager->create($user);

            if (false === $result) {
                throw new Exception('Erreur lors de la création de l\'utilisateur');
            }

            return ['Félicitations ! Vous êtes désormais inscrit et vous pouvez dès à présent poster des commentaires'];
        }

        return $validator->getErrors();
    }

    /**
     * @param array $user
     * @param array $data
     *
     * @throws Exception
     * @throws ReflectionException
     *
     * @return array
     */
    public function updateUser(array $user, array $data): array
    {
        $validator = ValidatorFactory::create(ValidatorFactory::UPDATE_USER, $data, $this->userManager);
        $user['base_infos'] = array_merge($user['base_infos'], $data);

        if ($validator->isValid()) {
            $updatedUser = new User($user['base_infos']);

            $result = $this->userManager->update($updatedUser);

            if (false === $result) {
                throw new Exception('Erreur lors de la mise à jour de l\'utilisateur');
            }

            return ['Utilisateur mis à jour'];
        }

        return $validator->getErrors();
    }

    /**
     * @param array $user
     *
     * @throws ReflectionException
     * @throws Exception
     *
     * @return bool
     */
    public function deleteUser(array $user): bool
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

            throw $e;
        }

        return true;
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
