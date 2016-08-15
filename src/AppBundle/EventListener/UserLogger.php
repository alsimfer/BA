<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class UserLogger
{
    
    protected $em;   

    protected $mappingActions = array(
        'patientCreate' => array('Patienten', 'erstellen'),
        'arrangementCreate' => array('Kurse', 'erstellen'),
        'coachingCreate' => array('Coachings', 'erstellen'),
        'medCheckupCreate' => array('Untersuchungen', 'erstellen'),
        'patientArrangementCreate' => array('Kursverläufe', 'erstellen'),
        'userCreate' => array('Benutzer', 'erstellen'),
    );  

    public function __construct(EntityManager $em)
    {
        
        $this->em = $em;                         
    }    

    public function onKernelController(FilterControllerEvent $event)
    {     
        $request = $event->getRequest();
        $session = $request->getSession();
        $userId = $session->get('user_id');        

        $user = $this->em->getRepository('AppBundle:SysUser')->findOneById($userId);    
        if (!$user) {
            return;
        }
        
        $params = explode('::', $request->attributes->get('_controller'));        
    
        $actionName = substr($params[1],0,-6);
        
        if(array_key_exists($actionName, $this->mappingActions) === FALSE) {
            return;
        } else {
            $field = $this->mappingActions[$actionName][0];
            $action = $this->mappingActions[$actionName][1];

            $objectId = 12;
            $dateTime = date("Y-m-d H:i:s")

            // dump($field);
            // dump($action);
            // die();
        }
    }
} 

