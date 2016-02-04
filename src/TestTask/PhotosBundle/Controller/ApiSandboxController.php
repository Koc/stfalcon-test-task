<?php

namespace TestTask\PhotosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApiSandboxController extends Controller
{
    public function sandboxAction($api_method)
    {
        $templates = array(
            'delete_photo' => '',
            'post_photo' => '@TestTaskPhotos/Api/postPhoto.html.twig',
            'delete_tags' => '@TestTaskPhotos/Api/deleteTags.html.twig',
        );

        return $this->render($templates[$api_method]);
    }
}
