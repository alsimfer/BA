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
    
    public function __construct(Router $router, EntityManager $em)
    {
        $this->router = $router;
        $this->em = $em;                         
    }    


    public function onKernelRequest(GetResponseEvent $event)
    {        
        // Check if user is logged in
        $request = $event->getRequest();
        $session = $request->getSession();
        $userId = $session->get('user_id');

        $user = $this->em->getRepository('AppBundle:SysUser')->findOneById($userId);    
        if (is_null($user)) {
            $route = 'loginPage';
            $route2 = 'passwordPage';

            if ($route === $event->getRequest()->get('_route') || $route2 === $event->getRequest()->get('_route')) {
                return;
            }

            $url = $this->router->generate($route);
            $response = new RedirectResponse($url);
            $event->setResponse($response);
            return;            
        } else {
            $event->getRequest()->attributes->set('user', $user);    
        }        

        // Get authenticated navigation for the logged in user
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

        // Check edit, info, delete urls.
        $idPermitted = true;
        // If there is a digit on the end remove it.
        $pattern = '/(\d+)\D*\z/';
        preg_match($pattern, $requestUri, $matches);            
        if (empty($matches) === FALSE) {
            $requestUri = str_replace("/".$matches[0], "", $requestUri);    
            // Collect ids of all relevant to this user patients.
            $patientIdsPermittedArray = array();
            $patientIdsPermitted = $this->em->getRepository('AppBundle:Patient')->findIdsRelevantToUser($user);
            foreach($patientIdsPermitted as $key => $value) {
                $patientIdsPermittedArray[] = $value['id'];
            }    
            if (strpos($requestUri, 'patients') !== FALSE && !in_array($matches[0], $patientIdsPermittedArray)) {
                $idPermitted = FALSE;
            }
        }        

        if (!in_array($requestUri, $urlsPermittedArray) || $idPermitted === FALSE) {
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

