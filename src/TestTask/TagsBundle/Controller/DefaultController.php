<?php

namespace TestTask\TagsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TestTaskTagsBundle:Default:index.html.twig');
    }
}
