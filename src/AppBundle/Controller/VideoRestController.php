<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class VideoRestController extends Controller
{
    public function getVideoIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $video = $em->getRepository('AppBundle:Video')->find($id);

        if(!is_object($video)) {
            throw $this->createNotFoundException();
        }

        return $video;
    }

    public function getVideoNameAction($name)
    {
        $videos = array();

        if(strlen($name) >= 3) {
            $repository = $this->getDoctrine()->getRepository('AppBundle:Video');
            $videos = $repository->searchVideo($name);
        }

        return $videos;
    }

    public function getVideosRecentAction($limit)
    {
        $em = $this->getDoctrine()->getManager();
        $videos = $em->getRepository('AppBundle:Video')->mostRecent($limit);

        return $videos;
    }

    public function getVideosSharedAction($limit)
    {
        $em = $this->getDoctrine()->getManager();
        $videos = $em->getRepository('AppBundle:Video')->mostShared($limit);

        return $videos;
    }

    public function getVideosViewedAction($limit)
    {
        $em = $this->getDoctrine()->getManager();
        $videos = $em->getRepository('AppBundle:Video')->mostViewed($limit);

        return $videos;
    }
}
