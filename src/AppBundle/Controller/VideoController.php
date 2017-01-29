<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use AppBundle\Entity\Video;
use AppBundle\Form\VideoType;

/**
 * Video controller.
 *
 */
class VideoController extends Controller
{

    /**
     * Lists all Video entities.
     *
     */
    public function indexAction()
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Video')->findByUser($user);

        return $this->render('AppBundle:Video:index.html.twig', array(
            'entities' => $entities, 'user' => $user
        ));
    }

    /**
     * Shows a video if you have the rights
     *
     */
    public function viewAction(Video $video)
    {
        $securityContext = $this->container->get('security.context');
        if($video->getStatus() == "link"){
            throw $this->createNotFoundException('You must have the share link to view this video');
        }
        else if($video->getStatus() == "private" && !$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
        else {
            $nbViewCount = $video->getViewCount();
            $video->setViewCount($nbViewCount+1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();

            return $this->render('AppBundle:Video:view.html.twig', array(
                'entity' => $video
            ));
        }
    }

    /**
     * Shows a video if status is link and you have the right link
     *
     */
    public function viewLinkAction($hash)
    {
        $em = $this->getDoctrine()->getManager();

        $video = $em->getRepository('AppBundle:Video')->findOneByHash(array('hash' => $hash));

        if ($video != null && $video->getStatus() == "link") {
            $nbViewCount = $video->getViewCount();
            $nbShareCount = $video->getShareCount();

            $video->setViewCount($nbViewCount+1);
            $video->setShareCount($nbShareCount+1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();

            return $this->render('AppBundle:Video:view.html.twig', array(
                'entity' => $video
            ));
        }
        else {
            throw $this->createNotFoundException("This link doesn't link to a video");
        }
    }

    /**
     * Shows a list of video
     *
     */
    public function viewListAction($type)
    {
        $em = $this->getDoctrine()->getManager();

        if($type == 'shared')
            $videos = $em->getRepository('AppBundle:Video')->mostShared();
        else if($type == 'viewed')
            $videos = $em->getRepository('AppBundle:Video')->mostViewed();
        else
            $videos = $em->getRepository('AppBundle:Video')->mostRecent();

        return $this->render('AppBundle:Video:view_list.html.twig', array(
            'videos' => $videos,
            'type' => $type
            )
        );
    }

    /**
     * Displays a form to edit an existing Video entity.
     *
     */
    public function editAction($id)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Video')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Video entity.');
        }

        if($user != $entity->getUser())
            throw $this->createNotFoundException('This Video is not yours !');

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AppBundle:Video:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Video entity.
    *
    * @param Video $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Video $entity)
    {
        $form = $this->createForm(new VideoType(), $entity, array(
            'action' => $this->generateUrl('user_video_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Video entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Video')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Video entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('user_video_edit', array('id' => $id)));
        }

        return $this->render('AppBundle:Video:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Video entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Video')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Video entity.');
            }

            if($user != $entity->getUser())
                throw $this->createNotFoundException('This Video is not yours !');

            unlink($entity->getAbsolutePath());
            unlink($entity->getImageAbsolutePath());

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user_video'));
    }

    /**
     * Creates a form to delete a Video entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_video_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * @Template()
     */
    public function uploadAction()
    {
        $video = new Video();
        $video->setUser($this->getUser());

        $form = $this->createFormBuilder($video)
            ->add('title', 'text')
            ->add('description', 'text')
            ->add('status', 'choice', array(
                'choices'   => array('private' => 'Private', 'link' => 'Link', 'public' => 'Public'),
                'required'  => true))
            ->add('file', 'file')
            ->getForm()
        ;

        $error = 'Something wrong has been detected !';

        if ($this->getRequest()->isMethod('POST')) {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                $file = $video->getFile();

                if($file->getClientOriginalExtension() == 'mp4' && $file->getClientSize() <= $file->getMaxFilesize()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($video);
                    $em->flush();

                    $video->setHash(md5($video->getId()));
                    $video->setShareUrl("/video/link/".$video->getHash());

                    $isUploaded = $video->upload();

                    if(!$isUploaded) {
                        $em->remove($video);
                        $em->flush();

                        return array('form' => $form->createView(), 'error' => $error);
                    }
                    else {
                        chdir($video->getUploadRootDir());

                        exec("ffmpeg -i ".$video->getPath()." -ss 00:00:05.000 -f image2 -vframes 1 ".$video->getId().".png");

                        $em->persist($video);
                        $em->flush();
                    }

                    return $this->redirect($this->generateUrl('user_video'));
                }
                else
                    return array('form' => $form->createView(), 'error' => $error);
            }
            else
                return array('form' => $form->createView(), 'error' => $error);
        }

        return array('form' => $form->createView());
    }
}
