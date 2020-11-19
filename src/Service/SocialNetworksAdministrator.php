<?php

namespace App\Service;

use App\Core\Session;
use App\Core\Validation\Validator;
use App\Exceptions\DatabaseException;
use App\Exceptions\SocialNetWorkException;
use App\Managers\SocialNetworkManager;
use App\Model\SocialNetwork;
use Exception;
use ReflectionException;

class SocialNetworksAdministrator
{
    /**
     * @var SocialNetworkManager
     */
    private SocialNetworkManager $socialNetWorkManager;
    /**
     * @var Session
     */
    private Session $session;
    /**
     * @var array|string[]
     */
    private array $iconNames = [
        'linkedin' => 'fa-linkedin',
        'instagram' => 'fa-instagram',
        'youtube' => 'fa-youtube',
        'facebook' => 'fa-facebook',
        'twitter' => 'fa-twitter',
        'github' => 'fa-github',
    ];

    /**
     * SocialNetworksAdministrator constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->socialNetWorkManager = new SocialNetworkManager();
    }

    /**
     * @param array $data
     *
     * @throws DatabaseException
     * @throws ReflectionException
     * @throws Exception
     */
    public function createSocialNetwork(array $data): void
    {
        $validator = (new Validator($data))->getSocialNetWorkValidator();
        if ($validator->isValid()) {
            $data['icon_name'] = $this->getIconName($data['name']);
            $socialNetWork = new SocialNetwork($data);
            $this->socialNetWorkManager->create($socialNetWork);

            $this->session->addMessages('Nouveau réseau social créé avec succès');

            return;
        }

        $this->session->addMessages($validator->getErrors());
    }

    /**
     * @param SocialNetwork $socialNetWork
     * @param array         $data
     *
     * @throws DatabaseException
     * @throws ReflectionException
     * @throws Exception
     */
    public function updateSocialNetwork(SocialNetwork $socialNetWork, array $data): void
    {
        $validator = (new Validator($data))->getSocialNetWorkValidator();
        if ($validator->isValid()) {
            $data['icon_name'] = $this->getIconName($data['name']);
            $socialNetWork->hydrate($data);
            $this->socialNetWorkManager->update($socialNetWork);

            $this->session->addMessages('Réseau social mis à jour');

            return;
        }

        $this->session->addMessages($validator->getErrors());
    }

    /**
     * @param SocialNetwork $socialNetWork
     *
     * @throws SocialNetWorkException
     */
    public function deleteSocialNetWork(SocialNetwork $socialNetWork): void
    {
        try {
            $this->socialNetWorkManager->delete($socialNetWork);
        } catch (Exception $e) {
            throw SocialNetWorkException::delete($socialNetWork->getId());
        }

        $this->session->addMessages('Réseau social supprimé');
    }

    private function getIconName(string $socialNetWorkName): string
    {
        if (\array_key_exists($socialNetWorkName, $this->iconNames)) {
            return $this->iconNames[$socialNetWorkName];
        }
    }
}
