<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $most_shared = $em->getRepository('AppBundle:Video')->mostShared(4);
        $most_viewed = $em->getRepository('AppBundle:Video')->mostViewed(4);
        $most_recent = $em->getRepository('AppBundle:Video')->mostRecent(12);

        return $this->render('AppBundle:Index:index.html.twig', array(
            'mostShared' => $most_shared,
            'mostViewed' => $most_viewed,
            'mostRecent' => $most_recent,
            )
        );
    }

    public function searchAction(Request $request)
    {
        if ($request->getMethod() == 'GET' && isset($_GET['search']) && strlen($_GET['search']) >= 3) {
            $text = $_GET['search'];
            $search = null;

            $repository = $this->getDoctrine()->getRepository('AppBundle:Video');
            $search = $repository->searchVideo($text);

            return $this->render('AppBundle:Index:search.html.twig', array(
                'search' => $search, 'request' => true
            ));
        }

        return $this->render('AppBundle:Index:search.html.twig', array(
            'request' => false
        ));
    }
}
