<?php

namespace AppBundle\EventListener;

use AppBundle\Controller\AuthenticationController;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ActionAuthenticator
{
    protected $router;
    protected $em;     

    /**
     * @param Router
     */
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
            return;
        }   

        $navRules = $this->em->getRepository('AppBundle:NavigationRules')->findByUserGroup($user->getUserGroup());        

        $urlsPermittedArray = array();

        // Mainpage is always accessible
        $urlsPermittedArray[] = "/";
        $urlsPermittedArray[] = "/no-such-page";
        $urlsPermittedArray[] = "/login";
        $urlsPermittedArray[] = "/password-issue";
        $urlsPermittedArray[] = "/logout";
        $urlsPermittedArray[] = "/user/settings";


        foreach ($navRules as $rule) {
            $buffer = explode(',', $rule->getUrlsPermitted());
            foreach ($buffer as $key => $value) {
                if (strlen($value) > 0) {
                    $urlsPermittedArray[] = $value;
                }
            }            
        }
        
        $requestUri = $request->getRequestURI();    

        // If there is a digit on the end remove it
        $pattern = '/(\d+)\D*\z/';
        preg_match($pattern, $requestUri, $matches);

        // TODO: Check if we are allowed to see item in matches[0]. E.g. /patients/info/3 -> matches[0] = 3
        if (empty($matches) === FALSE) {
            $requestUri = str_replace("/".$matches[0], "", $requestUri);        
        }

        if (!in_array($requestUri, $urlsPermittedArray)) {
            $route = 'accessDeniedPage';

            if ($route === $event->getRequest()->get('_route')) {
                return;
            }

            $url = $this->router->generate($route);
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }    
    
    }
} 

