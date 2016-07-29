<?php

namespace AppBundle\Service;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Doctrine\ORM\EntityManager;

class Util extends Controller
{

    protected $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function checkLoggedUser(Request $request) {
        $session = $request->getSession();
        $userId = $session->get('user_id');

        if (!$userId) {
            return FALSE;
        } else {
            $sysUser = $this->manager->getRepository('AppBundle:SysUser')->findOneById($userId);
            return $sysUser;
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
