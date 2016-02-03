<?php

namespace TestTask\PhotosBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TestTask\PhotosBundle\Entity\Photo;
use TestTask\PhotosBundle\Model\PhotosCollection;

// http://symfony.com/doc/master/bundles/FOSRestBundle/5-automatic-route-generation_single-restful-controller.html

/**
 */
class ApiController extends Controller
{
    /**
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", nullable=true, description="Page number.")
     * @Rest\View()
     */
    public function getPhotosAction($page)
    {
        $qb = $this
            ->getDoctrine()
            ->getRepository(Photo::class)
            ->createQueryBuilder('photo');

        $pagerfanta = (new Pagerfanta(new DoctrineORMAdapter($qb, false)))
            ->setMaxPerPage(10)
            ->setCurrentPage($page);

//        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
//        $path = $helper->asset($entity, 'image');

        return new PhotosCollection($pagerfanta);
    }

    public function deletePhotoAction($id)
    {
    }
}
