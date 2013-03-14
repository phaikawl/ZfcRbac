<?php

namespace ZfcRbac\Firewall\Listener;

use InvalidArgumentException;
use Zend\Mvc\MvcEvent;

class ActionResource
{
    /**
     * @param MvcEvent $e
     */
    public static function onRoute(MvcEvent $e)
    {
        $app         = $e->getTarget();
        $rbacService = $app->getServiceManager()->get('ZfcRbac\Service\Rbac');
        $match       = $app->getMvcEvent()->getRouteMatch();
        $controller  = $match->getParam('controller');
        $action      = $match->getParam('action');
        $resource    = sprintf('%s:%s', $controller, $action);

        if (!$rbacService->getFirewall('actionResource')->isGranted($resource)) {
            $e->setError($rbacService::ERROR_CONTROLLER_UNAUTHORIZED)
              ->setParam('identity', $rbacService->getIdentity())
              ->setParam('controller', $controller)
              ->setParam('action', $action);

            $app->getEventManager()->trigger('dispatch.error', $e);
        }
    }
}
