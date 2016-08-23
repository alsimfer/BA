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
        $pattern = '/(\d+)\D*\z/';
        preg_match($pattern, $requestUri, $matches);    

        // If URL is ending with a digit (id), check if user is allowed to see it.
        if (empty($matches) === FALSE) {
            // If there is a digit on the end remove it.
            $requestUri = str_replace("/".$matches[0], "", $requestUri); 
            $idPermitted = $this->checkId($user, $matches, $requestUri);            
        }        

        // Under /_wdt symfony call Web Toolbar. Such requests should be always permitted.
        if (
            (!in_array($requestUri, $urlsPermittedArray) || $idPermitted === FALSE) 
            && (strpos($requestUri, '_wdt') === FALSE) 
        ) {
            $route = 'accessDeniedPage';

            if ($route === $event->getRequest()->get('_route')) {
                return;
            }

            $url = $this->router->generate($route);
            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }    
    
    }

    // Check if the url ending with a digit is allowed for this user.
    private function checkId($user, $matches, $requestUri) {                   
        // Collect ids of all relevant to this user entities.        
        $patientIdsPermittedArray = array();
        $medCheckupIdsPermittedArray = array();
        $coachingIdsPermittedArray = array();
        $patArrRefIdsPermittedArray = array();

        $patientIdsPermitted = $this->em->getRepository('AppBundle:Patient')->findIdsRelevantToUser($user);
        $medCheckupIdsPermitted = $this->em->getRepository('AppBundle:MedCheckup')->findIdsRelevantToUser($user);
        $coachingIdsPermitted = $this->em->getRepository('AppBundle:Coaching')->findIdsRelevantToUser($user);
        $patArrRefIdsPermitted = $this->em->getRepository('AppBundle:PatientArrangementReference')->findIdsRelevantToUser($user);
        foreach($patientIdsPermitted as $key => $value) {
            $patientIdsPermittedArray[] = $value['id'];
        }    
        foreach($medCheckupIdsPermitted as $key => $value) {
            $medCheckupIdsPermittedArray[] = $value['id'];
        }    
        foreach($coachingIdsPermitted as $key => $value) {
            $coachingIdsPermittedArray[] = $value['id'];
        }    
        foreach($patArrRefIdsPermitted as $key => $value) {
            $patArrRefIdsPermittedArray[] = $value['id'];
        }    
        if (strpos($requestUri, 'patients') !== FALSE && !in_array($matches[0], $patientIdsPermittedArray)) {
            return FALSE;
        }
        if (strpos($requestUri, 'med-checkups') !== FALSE && !in_array($matches[0], $medCheckupIdsPermittedArray)) {
            return FALSE;
        }
        if (strpos($requestUri, 'coachings') !== FALSE && !in_array($matches[0], $coachingIdsPermittedArray)) {
            return FALSE;
        }
        if (strpos($requestUri, 'patient-arrangements') !== FALSE && !in_array($matches[0], $patArrRefIdsPermittedArray)) {
            return FALSE;
        }

        return TRUE;
    }
} 

