<?php

namespace AppBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Controller\AuthenticationController;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ActionAuthenticator extends Controller
{
    protected $router;
    protected $em;    
    protected $tokenStorage; 
    
    public function __construct(Router $router, EntityManager $em, TokenStorage $tokenStorage)
    {
        $this->router = $router;
        $this->em = $em;                         
        $this->tokenStorage = $tokenStorage;
    }    


    public function onKernelRequest(GetResponseEvent $event)
    {   
        $request = $event->getRequest();     
        $requestUri = $request->getRequestUri();

        // In profiler request there is no tokenStorage content. The both Uris are permitted for everyone.
        if (
            strpos($requestUri, '_profiler') !== FALSE
            || strpos($requestUri, '_wdt') !== FALSE
            || strpos($requestUri, 'login') !== FALSE
            || strpos($requestUri, 'logout') !== FALSE
        ) {
            return;
        }
    
        // Get logged user.
        $user = $this->tokenStorage->getToken()->getUser();
        // dump($user); die;
        // Get authenticated navigation for the logged in user.
        $navRules = $this->em->getRepository('AppBundle:NavigationRules')->findByUserGroup($user->getUserGroup());        

        $urlsPermittedArray = array();

        // Pages which are accessible for any logged user.
        $urlsPermittedArray[] = "/";
        $urlsPermittedArray[] = "/no-such-page";
        $urlsPermittedArray[] = "/login";
        $urlsPermittedArray[] = "/password";
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

