<?php

namespace TestTask\PhotosBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TestTask\PhotosBundle\Model\PhotosCollection;

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

    public function deletePhotoAction($id)
    {
    }
}
