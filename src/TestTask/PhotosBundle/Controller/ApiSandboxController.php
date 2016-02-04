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

        $apiConfig = array(
            'post_photo' => array(
                'uri' => '/photos',
                'method' => 'post',
                'fields' => array(
                    'image' => array(
                        'label' => 'Фото',
                        'type' => 'file',
                        'required' => true,
                    ),
                    'tags' => array(
                        'label' => 'Теги',
                        'multuple' => true,
                        'required' => true,
                    )
                ),
            ),
        );


        return $this->render($templates[$api_method], array('config' => $apiConfig[$api_method]));
    }
}
