<?php

namespace AppBundle\EventListener;

use AppBundle\Controller\TokenAuthenticatedController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserIdListener
{
    
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        $session = $event->getRequest()->getSession();
        $userId = $session->get('user_id');
            
        if ($userId) {
            throw new AccessDeniedHttpException('Sie sind nicht authentifiziert!');
        }
        
    }
}