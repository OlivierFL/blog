<?php

namespace App\Core\Service;

use App\Core\Validation\ValidatorFactory;
use App\Managers\AdminManager;
use App\Managers\UserManager;
use App\Model\User;
use Exception;
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
     * UserAdministrator constructor.
     */
    public function __construct()
    {
        $this->userManager = new UserManager();
        $this->adminManager = new AdminManager();
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
            $user->setCreatedAt((new \DateTime())->format('Y-m-d H:i:s'));
            $user->setUpdatedAt((new \DateTime())->format('Y-m-d H:i:s'));
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
     * @param $user
     *
     * @throws ReflectionException
     * @throws Exception
     *
     * @return array
     */
    public function updateUser($user): array
    {
        $validator = ValidatorFactory::create(ValidatorFactory::UPDATE_USER, $_POST, $this->userManager);

        if ($validator->isValid()) {
            $updatedUser = (new User($user['base_infos']))
                ->setUserName($_POST['user_name'])
                ->setFirstName($_POST['first_name'])
                ->setLastName($_POST['last_name'])
                ->setEmail($_POST['email'])
                ->setRole($_POST['role'])
            ;
            $updatedUser->setUpdatedAt((new \DateTime())->format('Y-m-d H:i:s'));

            $result = $this->userManager->update($updatedUser);

            if (false === $result) {
                throw new Exception('Erreur lors de la mise à jour de l\'utilisateur');
            }

            return ['Utilisateur mis à jour'];
        }

        return $validator->getErrors();
    }
}
