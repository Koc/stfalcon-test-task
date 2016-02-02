<?php

namespace TestTask\PhotosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TestTaskPhotosBundle:Default:index.html.twig');
    }
}
