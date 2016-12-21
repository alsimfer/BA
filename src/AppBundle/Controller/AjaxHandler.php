<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\SysUser;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Arrangement;
use AppBundle\Entity\MedCheckup;
use AppBundle\Entity\PatientArrangementReference;
use AppBundle\Controller\Util;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class AjaxHandler extends Controller
{    
    /**
     * @Route("/ajax", name="ajax")
     * @Method({"GET", "POST"})
     */
    public function ajax(Request $request)
    {        
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax) {
            $id = $request->request->get('id');
            $progressItems = $this->getDoctrine()->getRepository('AppBundle:Coaching')->getForGraph($id);
            
            foreach ($progressItems as $index => $value) {            
                // Add date as a string to make Front End easier.
                $progressItems[$index]['date'] = $value['mondayThisWeek']->format('Y-m-d');                    
            }



            return new Response(json_encode($progressItems));
        }

        
        $id = 86;
        $progressItems = $this->getDoctrine()->getRepository('AppBundle:Coaching')->getForGraph(86);
        foreach ($progressItems as $index => $value) {            
            // Add date as a string to make Front End easier.
            $progressItems[$index]['date'] = $value['mondayThisWeek']->format('Y-m-d');                    
        }

        $nullIndexArray = array();
        // If patient weight is not there, fill it with "expected" value.
        if ($progressItems[$index]['patient_weight'] === NULL) {
            $nullIndexArray[] = $index;
            
        }

    
        dump($progressItems); dump($nullIndexArray); die();
    }
}
