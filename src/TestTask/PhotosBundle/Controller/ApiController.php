<?php

namespace TestTask\PhotosBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Adapter\CallbackAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use TestTask\PhotosBundle\Entity\Photo;
use TestTask\PhotosBundle\Form\PhotoType;
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
     * @Rest\QueryParam(name="tags", requirements=".+", map=true, description="Tags for filtering.")
     * @Rest\View(template="@TestTaskPhotos/Api/getPhotos.html.twig")
     */
    public function getPhotosAction($page, array $tags = null)
    {
        $doctrine = $this->getDoctrine();
        $tags = (array)$tags;

        if ($tags) {
            $tags = $doctrine->getRepository('TestTaskTagsBundle:Tag')->findBy(array('title' => $tags));
        }

        $photoRepository = $doctrine->getRepository('TestTaskPhotosBundle:Photo');

        $qb = $photoRepository->getPhotosQb($tags);

        $innerAdapter = new DoctrineORMAdapter($qb, false);
        // wrap adapter for getting possibility load tags as primitives
        $adapter = new CallbackAdapter(
            function () use ($innerAdapter) {
                return $innerAdapter->getNbResults();
            },
            function ($offset, $length) use ($innerAdapter, $photoRepository) {
                $results = $innerAdapter->getSlice($offset, $length);

                $photoRepository->attachTagsToPhotos(iterator_to_array($results));

                return $results;
            }
        );

        $pagerfanta = (new Pagerfanta($adapter))
            ->setMaxPerPage(10)
            ->setCurrentPage($page ?: 1);

        return new PhotosCollection($pagerfanta);
    }

    /**
     * @ApiDoc(description="Uploads photo with tags.")
     *
     * @Rest\FileParam(name="image", image=true, description="Image to upload.")
     * @Rest\RequestParam(name="tags", requirements=".+", nullable=false, map=true, description="Tags that associates photo.")
     * @Rest\View()
     */
    public function postPhotoAction(ParamFetcher $paramFetcher, array $tags)
    {
        $em = $this->getDoctrine()->getManager();

        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);

        if ($tags) {
            $tags = $em->getRepository('TestTaskTagsBundle:Tag')->findOrCreateByTitles($tags);
        }

        $form->submit($paramFetcher->all());

        if (!$form->isValid()) {
            return $form->getErrors();
        }

        foreach ($tags as $tag) {
            $photo->addTag($tag);
        }

        $em->persist($photo);
        $em->flush();

        return array('photo' => $photo);
    }

    /**
     * @ApiDoc(description="Associate photo with tags.")
     *
     * @ParamConverter("photo", class="TestTaskPhotosBundle:Photo")
     *
     * @Rest\Post("/photos/{id}/tags")
     * @Rest\RequestParam(name="tags", requirements=".+", nullable=false, map=true, description="Tags that associates photo.")
     * @Rest\View()
     */
    public function postTagsToPhotoAction(Photo $photo, array $tags)
    {
        $em = $this->getDoctrine()->getManager();

        //TODO: add validation and maybe form

        if ($tags) {
            $tags = $em->getRepository('TestTaskTagsBundle:Tag')->findOrCreateByTitles($tags);
        }

        foreach ($tags as $tag) {
            $photo->addTag($tag);
        }

        $em->persist($photo);
        $em->flush();

        return array('photo' => $photo);
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
     * @ApiDoc(description="Deletes tags by they titles.")
     *
     * @Rest\Post("/tags")
     * @Rest\RequestParam(name="tags", requirements=".+", map=true, allowBlank=false, description="Tags titles for deletion.")
     * @Rest\View()
     */
    public function deleteTagsAction(array $tags)
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
