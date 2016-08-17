<?php

namespace AppBundle\EventListener;

use AppBundle\Controller\TokenAuthenticatedController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Doctrine\ORM\EntityManager;

class UserIdListener
{
    protected $router;
    protected $em;     
    
    public function __construct(Router $router, EntityManager $em)
    {
        $this->router = $router;
        $this->em = $em;                         
    } 

    public function onKernelRequest(GetResponseEvent $event)
    {        
        $request = $event->getRequest();
        $session = $request->getSession();
        $userId = $session->get('user_id');

        $user = $this->em->getRepository('AppBundle:SysUser')->findOneById($userId);    
        if (!$user) {
            $route = 'loginPage';

            if ($route === $event->getRequest()->get('_route')) {
                return;
            }

            $url = $this->router->generate($route);
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }   
    
    }
}