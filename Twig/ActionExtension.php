<?php

/**
 * This file is part of the NotificationBundle package.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace merk\NotificationBundle\Twig;

use merk\NotificationBundle\Model\ActionManagerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ActionExtension extends \Twig_Extension
{
    protected $actionManager;
    protected $securityContext;

    public function __construct(ActionManagerInterface $actionManager, SecurityContextInterface $securityContext)
    {
        $this->actionManager = $actionManager;
        $this->securityContext     = $securityContext;
    }

    public function getActionCount(UserInterface $user = null)
    {
        if (null === $user) {
            $user = $this->securityContext->getToken()->getUser();
        }

        return count($this->actionManager->getActions($user));
    }

    public function getNotifications(UserInterface $user = null)
    {
        if (null === $user) {
            $user = $this->securityContext->getToken()->getUser();
        }

        return $this->actionManager->getActions($user);
    }

    public function getNotification($id)
    {
        return $this->actionManager->getAction($id);
    }

    public function getGlobals()
    {
        return array(
            'merk_notification.notification_count' => new \Twig_Function_Method($this, 'getNotificationCount'),
            'merk_notification.notifications' => new \Twig_Function_Method($this, 'getNotifications'),
            'merk_notification.notification' => new \Twig_Function_Method($this, 'getNotification'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'merk_notification';
    }
}