<?php

namespace AppBundle\Controller;

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
use AppBundle\Controller\Util;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class LogController extends Controller
{    
    /**
     * @Route("/logs", name="logsPage")
     */
    public function logsAction(Request $request)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $logs = $this->getDoctrine()->getRepository('AppBundle:Log')->findBy(array(), array('id' => 'DESC'), 1000, 0);
        return $this->render('log/logsPage.html.twig', 
            array(
                'title' => 'AOK | Änderungsprotokolle',
                'user' => $sysUser,
                'logs' => $logs,
                
            )
        );
    }
    
    /**
     * @Route("/logs/info/{id}", name="logInfoPage")
     */
    public function logInfoAction(Request $request, $id)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $log = $this->getDoctrine()->getRepository('AppBundle:Log')->findOneById($id);
        
        return $this->render('log/logInfoPage.html.twig', 
            array(
                'title' => 'AOK | Änderungsprotokoll',
                'user' => $sysUser,
                'log' => $log,
            )
        );
    }
    
}
