<?php

namespace App\Controller;

use App\Controller\Admin\DashboardController;
use App\Entity\User;
use App\Service\UserSecurityService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Event\AbstractLifecycleEvent;

class AdminController extends DashboardController
{
    /**
     * @var UserSecurityService
     */
    private $userSecurityService;

    /**
     * AdminController constructor.
     * @param UserSecurityService $userSecurityService
     */
    public function __construct(UserSecurityService $userSecurityService)
    {
        $this->userSecurityService = $userSecurityService;
    }

    /**
     *
     *
     * @param $eventName
     * @param array $arguments
     */
    protected function dispatch($eventName, array $arguments = array())
    {
        $subject = isset($arguments['entity']) ? $arguments['entity'] : null;

        if ($subject instanceof User
            && in_array($eventName, [EasyAdminEvents::PRE_PERSIST, EasyAdminEvents::PRE_UPDATE])
        ) {
            $user = $this->request->request->get('user');
            $password = $user['password'];

            if (! empty(trim($password))) {
                $this->userSecurityService->setupUser($subject);
            }
        }

        parent::dispatch($eventName, $arguments);
    }
}