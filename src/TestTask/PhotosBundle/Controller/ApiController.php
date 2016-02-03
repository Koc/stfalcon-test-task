<?php

namespace TestTask\PhotosBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TestTask\PhotosBundle\Entity\Photo;
use TestTask\PhotosBundle\Model\PhotosCollection;
use TestTask\TagsBundle\Entity\Tag;

// http://symfony.com/doc/master/bundles/FOSRestBundle/5-automatic-route-generation_single-restful-controller.html

/**
 */
class ApiController extends Controller
{
    /**
     * @ApiDoc(description="List of photos with possibility filtering by tags.", resource=true)
     *
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page number.")
     * @Rest\QueryParam(name="tags", requirements=".+", array=true, description="Tags for filtering.")
     * @Rest\View()
     */
    public function getPhotosAction($page, array $tags = array())
    {
        $doctrine = $this->getDoctrine();

        if ($tags) {
            $tags = $doctrine->getRepository('TestTaskTagsBundle:Tag')->findBy(array('title' => $tags));
        }

        $qb = $doctrine
            ->getRepository('TestTaskPhotosBundle:Photo')
            ->getPhotosQb($tags);

        //TODO: eagerly load tags
        $pagerfanta = (new Pagerfanta(new DoctrineORMAdapter($qb, false)))
            ->setMaxPerPage(10)
            ->setCurrentPage($page);

        return new PhotosCollection($pagerfanta);
    }

    /**
     * @ApiDoc(description="Deletes photo.")
     *
     * @ParamConverter("photo", class="TestTaskPhotosBundle:Photo")
     *
     * @Rest\Delete("/photos/{id}")
     * @Rest\View()
     */
    public function deletePhotoAction(Photo $photo)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($photo);
        $em->flush();

        return array('status' => 'success');
    }

    /**
     * @ApiDoc(description="Deletes tags.")
     *
     * @Rest\Post("/tags")
     * @Rest\RequestParam(name="tags", requirements=".+", array=true, allowBlank=false, description="Tags titles for deletion.")
     * @Rest\View()
     */
    public function deleteTagAction(array $tags)
    {
        $em = $this->getDoctrine()->getManager();

        $tags = $em->getRepository('TestTaskTagsBundle:Tag')->findBy(array('title' => $tags));
        foreach ($tags as $tag) {
            $em->remove($tag);
        }

        $em->flush();

        return array('status' => 'success');
    }
}
