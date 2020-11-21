<?php

namespace App\Service;

use App\Core\Session;
use App\Core\Validation\Validator;
use App\Exceptions\FileDeleteException;
use App\Exceptions\FileUploadException;
use App\Exceptions\UserException;
use App\Managers\AdminManager;
use App\Managers\UserManager;
use App\Model\Admin;
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
     * @var Session
     */
    private Session $session;
    /**
     * @var FileUploader
     */
    private FileUploader $fileUploader;

    /**
     * UserAdministrator constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->userManager = new UserManager();
        $this->adminManager = new AdminManager();
        $this->session = $session;
        $this->fileUploader = new FileUploader();
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
     * @param Admin|User $user
     * @param array      $data
     *
     * @throws FileDeleteException
     * @throws FileUploadException
     * @throws UserException
     * @throws ReflectionException
     * @throws Exception
     */
    public function updateUser($user, array $data): void
    {
        $validator = (new Validator($data, $this->userManager))->getUserUpdateValidator();

        if ($validator->isValid()) {
            $user->hydrate($data);

            if (User::ROLE_ADMIN === $user->getRole()) {
                $this->uploadAdminFiles($data);
                $result = $this->userManager->updateAdmin($user);
            } else {
                $result = $this->userManager->update($user);
            }

            if (false === $result) {
                if ('admin' === $user->getRole()) {
                    $this->deleteAdminFiles($user);
                }

                throw UserException::update($user->getId());
            }

            $this->session->addMessages('Utilisateur mis à jour');

            return;
        }

        $this->session->addMessages($validator->getErrors());
    }

    /**
     * @param Admin|User $user
     *
     * @throws UserException
     * @throws Exception
     */
    public function deleteUser($user): void
    {
        $deletedUser = $this->anonymizeUser($user);

        try {
            if (User::ROLE_ADMIN === $user->getRole()) {
                $this->deleteAdminFiles($user);
                $this->adminManager->update($deletedUser);
            } else {
                $this->userManager->update($deletedUser);
            }
        } catch (Exception $e) {
            throw UserException::delete($deletedUser->getId());
        }

        $this->session->addMessages('Utilisateur supprimé');
    }

    /**
     * @param Admin|User $user
     *
     * @return Admin|User
     */
    private function anonymizeUser($user)
    {
        $anonymousUser = 'anonymous'.$user->getId();
        $user->setUserName($anonymousUser);
        $user->setFirstName($anonymousUser);
        $user->setLastName($anonymousUser);
        $user->setEmail($anonymousUser.'@example.com');

        if (User::ROLE_ADMIN === $user->getRole()) {
            $user->setDescription(null);
            $user->setUrlCV(null);
            $user->setAltUrlAvatar(null);
            $user->setUrlCV(null);
        }

        $user->setRole('ROLE_DISABLED');

        return $user;
    }

    /**
     * @param array $data
     *
     * @throws FileUploadException
     *
     * @return array
     */
    private function uploadAdminFiles(array $data): array
    {
        if (isset($_FILES) && 4 !== $_FILES['url_avatar']['error']) {
            $file = $this->fileUploader->checkFile($_FILES['url_avatar'], FileUploader::IMAGE);
            $data['url_avatar'] = $this->fileUploader->upload($file);
        }

        if (isset($_FILES) && 4 !== $_FILES['url_cv']['error']) {
            $file = $this->fileUploader->checkFile($_FILES['url_cv'], FileUploader::FILE);
            $data['url_cv'] = $this->fileUploader->upload($file);
        }

        return $data;
    }

    /**
     * @param Admin $admin
     *
     * @throws FileDeleteException
     */
    private function deleteAdminFiles(Admin $admin): void
    {
        if (null !== $admin->getUrlAvatar()) {
            $this->fileUploader->delete($admin->getUrlAvatar());
        }

        if (null !== $admin->getUrlCv()) {
            $this->fileUploader->delete($admin->getUrlCv());
        }
    }
}
