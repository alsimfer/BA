<?php

namespace AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\TextareaType; 
use Symfony\Component\Form\Extension\Core\Type\PasswordType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use AppBundle\Entity\SysUser;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Arrangement;
use AppBundle\Entity\MedCheckup;
use AppBundle\Entity\PatientArrangementReference;
use AppBundle\Entity\Log;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\EntityManager;

class Util extends Controller
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
        'userEdit' => array('Benutzer', 'bearbeiten'),
        'userDelete' => array('Benutzer', 'löschen'),
        'userSettings' => array('Benutzer', 'einstellen'),
    );  

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function checkLoggedUser(Request $request) {
        $session = $request->getSession();
        $userId = $session->get('user_id');

        if (!$userId) {
            return FALSE;
        } else {
            $sysUser = $this->em->getRepository('AppBundle:SysUser')->findOneById($userId);
            return $sysUser;
        }
    }

    public function getChanges($objectBefore, $objectAfter) {
        $afterArray = $objectAfter->iterateVisible();
        $beforeArray = $objectBefore->iterateVisible();
        $difference = array_merge(array_diff($afterArray, $beforeArray), array_diff($beforeArray, $afterArray));

        $changes = '';
// dump($difference);
// die();
        foreach($difference as $key => $value) {
            $changes .= $key.': ';
            $changes .= strlen($beforeArray[$key]) > 0 ? $beforeArray[$key].' => ' : 'Leer => ';
            $changes .= strlen($afterArray[$key]) > 0 ? $afterArray[$key]."\n" : 'Leer'."\n";
        }

        return $changes;
    }

    public function logAction(Request $request, $objectId, $objectBefore, $objectAfter) {
        $user = $this->checkLoggedUser($request);

        if (!$user) {
            return;
        }
        
        $actionName = "";
        $params = explode('::', $request->attributes->get('_controller'));                    
        if (array_key_exists(1, $params)) {
            $actionName = substr($params[1],0,-6);    
        } else {
            return;
        }
        
        if (array_key_exists($actionName, $this->mappingActions) === FALSE) {
            return;
        } else {
            $changes = $this->getChanges($objectBefore, $objectAfter);
            
            $field = $this->mappingActions[$actionName][0];
            $action = $this->mappingActions[$actionName][1];
            $dateTime = new \DateTime(date("Y-m-d H:i:s"));

            $log = new Log();
            $log->setUserLastName($user->getLastName());
            $log->setUserFirstName($user->getFirstName());
            $log->setField($field);
            $log->setAction($action);
            $log->setObjectId($objectId);
            $log->setDateTime($dateTime);
            $log->setLog($changes);
            
            $this->em->persist($log);
            $this->em->flush();       
        
        }   
    }
    

    public function sendEmail($subject, $object, $viewPath, $options) 
    {
        /**
         * Get help about setting up mailing on localhost
         * http://www.developerfiles.com/how-to-send-emails-from-localhost-mac-os-x-el-capitan/
         * $ sudo postfix status  
         * postfix/postfix-script: the Postfix mail system is not running 
         * $ sudo postfix start  
         * postfix/postfix-script: starting the Postfix mail system  
         */
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('alsimfer@gmail.com')
            ->setTo($object->getEmail())
            ->setBody(
                $this->renderView(
                    $viewPath.'.html.twig',
                    array('object' => $object, 'options' => $options)
                ),
                'text/html'
            );

        $this->get('mailer')->send($message);
    }

}
