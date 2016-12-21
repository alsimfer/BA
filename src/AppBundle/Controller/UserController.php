<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\Type\SysUserCreateType;
use AppBundle\Form\Type\SysUserEditType;
use AppBundle\Form\Type\SysUserSelfType;
use AppBundle\Form\Type\RegisterType;

use AppBundle\Entity\SysUser;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Arrangement;
use AppBundle\Entity\MedCheckup;
use AppBundle\Entity\PatientArrangementReference;
use AppBundle\Controller\Util;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UserController extends Controller
{    
    /**
     * @Route("/users", name="usersPage")
     */
    public function usersAction(Request $request)
    {        
        $users = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array(), array('id' => 'DESC'), 1000, 0);
        
        return $this->render('user/usersPage.html.twig', 
            array(
                'title' => 'AOK | Benutzer',
                'users' => $users,
                
            )
        );
    }

    public function sendTestEmail() 
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('adipositas.aok@gmail.com')
            ->setTo('yasru@yandex.ru')
            ->setBody(
                $this->renderView(
                    // app/Resources/views/email/registration.html.twig
                    'email/registration.html.twig',
                    array('name' => 'hallo')
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
    }


    /**
     * @Route("/register", name="registerPage")
     */
    public function registerAction(Request $request)
    {
        $user = new SysUser();
        $form = $this->createForm(RegisterType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user->setFirstName('test');
            $user->setLastName('test');
            $user->setAddress('test');
            $user->setSex('weiblich');
            $user->setPhoneNumber('test');
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            $users = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array(), array('id' => 'DESC'), 1000, 0);

            return $this->redirectToRoute('indexPage');
        }

        return $this->render(
            'security/register.html.twig',
            array('form' => $form->createView())
        );
    }


    /**
     * @Route("/users/create", name="createUserPage")
     */
    public function userCreateAction(Request $request)
    {        
        $user = new SysUser();
        $before = clone($user);

        $util = $this->get('util');
        $this->sendTestEmail();

        // Prepare selectField with userGroups for the form.
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery('select u from AppBundle\Entity\UserGroup u where u.id >= 2');        
        $userGroups = $q->getResult();

        $hospitals = $this->getDoctrine()->getRepository('AppBundle:Hospital')->findAll();

        $form = $this->createForm(SysUserCreateType::class, $user, array(
                'validation_groups' => array("create"),
                'hospitals' => $hospitals,
                'userGroups' => $userGroups
            )
        );

        $form->handleRequest($request);
  
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFirstName($form['firstName']->getData());
            $user->setLastName($form['lastName']->getData());
            $user->setEmail($form['email']->getData());
            $user->setPhoneNumber($form['phoneNumber']->getData());
            $user->setSex($form['sex']->getData());
            $user->setAddress($form['address']->getData());
            $user->setUserGroup($form['userGroup']->getData());
            $user->setHospital($form['hospital']->getData());
            $user->setUsername(NULL);

            $plainPassword = $form['password']->getData();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $util = $this->get('util');
            $util->logAction($request, $user->getId(), $before, $user);

            $this->addFlash('notice', 'Benutzer erfolgreich hinzugefügt');

#            $this->sendEmail('Registrierung erfolgreich abgeschlossen', $user, 'email/registration');

            return $this->redirectToRoute('usersPage');
        }
        
        return $this->render('user/userCreatePage.html.twig', array(
            'title' => 'AOK | Benutzer',
            'form' => $form->createView(),
            
        ));
    }

    /**
     * @Route("/users/edit/{id}", name="userEditPage")
     */
    public function userEditAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findOneById($id);
        $before = clone($user);

        // Prepare selectField with userGroups for the form.
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery('select u from AppBundle\Entity\UserGroup u where u.id >= 2');        
        $userGroups = $q->getResult();
        
        $hospitals = $this->getDoctrine()->getRepository('AppBundle:Hospital')->findAll();

        $form = $this->createForm(SysUserEditType::class, $user, array(
                'validation_groups' => array('edit'),
                'hospitals' => $hospitals,
                'userGroups' => $userGroups
            )
        );
                
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFirstName($form['firstName']->getData());
            $user->setLastName($form['lastName']->getData());
            $user->setEmail($form['email']->getData());
            $user->setPhoneNumber($form['phoneNumber']->getData());
            $user->setSex($form['sex']->getData());
            $user->setAddress($form['address']->getData());
            $user->setUserGroup($form['userGroup']->getData());
            $user->setHospital($form['hospital']->getData());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $util = $this->get('util');
            $util->logAction($request, $id, $before, $user);

            $this->addFlash('notice', 'Benutzer erfolgreich gespeichert');
            return $this->redirectToRoute('usersPage');
        }
        
        return $this->render('user/userEditPage.html.twig', array(
            'title' => 'AOK | Benutzer',
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/users/info/{id}", name="userInfoPage")
     */
    public function userInfoAction(Request $request, $id)
    {        
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findOneById($id);
        
        return $this->render('user/userInfoPage.html.twig', 
            array(
                'title' => 'AOK | Benutzer | Info',
                'sysUsers' => $sysUsers
            )
        );
    }



    /**
     * @Route("/users/delete/{id}", name="userDeletePage")
     */
    public function userDeleteAction(Request $request, $id)
    {        
        $user = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findOneById($id);
        $before = clone($user);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $util = $this->get('util');
        $util->logAction($request, $id, $before, $user);

        $this->addFlash('notice', 'Benutzer erfolgreich gelöscht');
        
        return $this->redirectToRoute('usersPage');
        
    }

     /**
     * @Route("/user/settings", name="userSettingsPage")
     */
    public function userSettingsAction(Request $request)
    {
        $sysUser = $this->getUser();
        $before = clone($sysUser);

        $form = $this->createForm(SysUserSelfType::class, $sysUser, array(
                'validation_groups' => array("create"),
            ));

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $sysUser->setFirstName($form['firstName']->getData());
            $sysUser->setLastName($form['lastName']->getData());
            $sysUser->setEmail($form['email']->getData());
            $sysUser->setPhoneNumber($form['phoneNumber']->getData());
            $sysUser->setSex($form['sex']->getData());
            $sysUser->setAddress($form['address']->getData());
            
            $plainPassword = $form['password']->getData();
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($sysUser, $plainPassword);
            $sysUser->setPassword($encoded);

            $em = $this->getDoctrine()->getManager();
            $em->persist($sysUser);
            $em->flush();

            $util = $this->get('util');
            $util->logAction($request, $sysUser->getId(), $before, $sysUser);

            $this->addFlash('notice', 'Einstellungen erfolgreich gespeichert');
            return $this->redirectToRoute('usersPage');
        }
        
        // same page as new User.
        return $this->render('user/userSettingsPage.html.twig', array(
            'title' => 'AOK | Einstellungen',
            'form' => $form->createView(),
        ));
    }

}
