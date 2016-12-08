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

class DefaultController extends Controller
{    

    /**
     * @Route("/", name="indexPage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/indexPage.html.twig', 
            array(
                'title' => 'AOK | Index',
                
                'pageHeader' => 'DB Schema'
            )
        );       
    }

    /**
     * @Route("/no-such-page", name="404Page")
     */
    public function pageNotFoundAction(Request $request)
    {
        return $this->render('404Page.html.twig', 
            array(
                'title' => 'AOK | Seite 404'                
            )
        );
    }

    /**
     * @Route("/access-denied", name="accessDeniedPage")
     */
    public function accessDeniedAction(Request $request)
    {
        return $this->render('AccessDeniedPage.html.twig', 
            array(
                'title' => 'AOK | Zugang verboten'                
            )
        );
    }
    
    /**
     * @Route("/admin")
     */
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }

}
