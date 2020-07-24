<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller
{
    /**
     * @Route("/add")
     */
    public function addAction()
    {
        return $this->render('AppBundle:Security:user_home.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/home")
     */
    public function redirectAction()
    {
        $authChecker=$this->container->get('security.authorization_checker');
        if($authChecker->isGranted('ROLE_ADMIN'))
    {
        return $this->render('@App/Security/admin_home.html.twig');
    }
        if($authChecker->isGranted('ROLE_USER'))
        {
            return $this->render('@App/Security/user_home.html.twig');
        }
        return $this->render('@FOSUser/Security/login.html.twig');
    }

}
