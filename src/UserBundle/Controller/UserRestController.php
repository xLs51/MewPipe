<?php

namespace UserBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserRestController extends Controller
{
    public function getUserIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find($id);

        if(!is_object($user)) {
            throw $this->createNotFoundException();
        }

        return $user;
    }

    public function getUserUsernameAction($username)
    {
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->findOneByUsername($username);

        if(!is_object($user)) {
            throw $this->createNotFoundException();
        }

        return $user;
    }


    public function getUserEmailAction($email)
    {
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->findOneByEmail($email);

        if(!is_object($user)) {
            throw $this->createNotFoundException();
        }

        return $user;
    }

}
