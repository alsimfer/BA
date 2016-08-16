<?php

namespace AppBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use AppBundle\Entity\Log;

class UserLogger
{
    
    protected $em;   

    protected $mappingActions = array(
        'patientCreate' => array('Patienten', 'erstellen'),
        'patientEdit' => array('Patienten', 'bearbeiten'),
        'patientDelete' => array('Patienten', 'löschen'),

        'arrangementCreate' => array('Kurse', 'erstellen'),
        'arrangementEdit' => array('Kurse', 'bearbeiten'),
        'arrangementDelete' => array('Kurse', 'löschen'),

        'coachingCreate' => array('Coachings', 'erstellen'),
        'coachingEdit' => array('Coachings', 'bearbeiten'),
        'coachingDelete' => array('Coachings', 'löschen'),

        'medCheckupCreate' => array('Untersuchungen', 'erstellen'),
        'medCheckupEdit' => array('Untersuchungen', 'bearbeiten'),
        'medCheckupDelete' => array('Untersuchungen', 'löschen'),

        'patientArrangementCreate' => array('Kursverläufe', 'erstellen'),
        'patientArrangementEdit' => array('Kursverläufe', 'bearbeiten'),
        'patientArrangementDelete' => array('Kursverläufe', 'löschen'),
        
        'userCreate' => array('Benutzer', 'erstellen'),
        'userCreate' => array('Benutzer', 'bearbeiten'),
        'userCreate' => array('Benutzer', 'löschen'),
    );  

    public function __construct(EntityManager $em)
    {
        
        $this->em = $em;                         
    }    

    public function onKernelResponse(FilterResponseEvent $event)
    {     
        $request = $event->getRequest();
        $session = $request->getSession();
        $userId = $session->get('user_id');        

        $user = $this->em->getRepository('AppBundle:SysUser')->findOneById($userId);    
        if (!$user) {
            return;
        }
        
        $params = explode('::', $request->attributes->get('_controller'));                    
        if (array_key_exists(1, $params)) {
            $actionName = substr($params[1],0,-6);    
        } else {
            return;
        }
        
        // toLog set a controller.
        if ($request->attributes->get('toLog') && $request->attributes->get('objectId')) {
            if (array_key_exists($actionName, $this->mappingActions) === FALSE) {
                return;
            } else {
                $field = $this->mappingActions[$actionName][0];
                $action = $this->mappingActions[$actionName][1];
                $dateTime = new \DateTime(date("Y-m-d H:i:s"));

                $log = new Log();
                $log->setUserLastName($user->getLastName());
                $log->setUserFirstName($user->getFirstName());
                $log->setField($field);
                $log->setAction($action);
                $log->setObjectId($request->attributes->get('objectId'));
                $log->setDateTime($dateTime);
                $log->setLog($request->attributes->get('toLog'));
                
                $this->em->persist($log);
                $this->em->flush();
                
                $request->attributes->remove('toLog');
                $request->attributes->remove('objectId');

            }   
        
        }                 
    }
} 

