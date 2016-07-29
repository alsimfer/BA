<?php

namespace AppBundle\EventListener;

use AppBundle\Controller\AuthenticationController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class ErrorRedirectListener
{
    
    protected $router;

    /**
     * @param Router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }


    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof NotFoundHttpException) {

            $route = '404Page';

            if ($route === $event->getRequest()->get('_route')) {
                return;
            }

            $url = $this->router->generate($route);
            $response = new RedirectResponse($url);
            $event = new GetResponseForExceptionEvent();
            $event->setResponse($response);

        }

        if ($exception instanceof AccessDeniedHttpException) {

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