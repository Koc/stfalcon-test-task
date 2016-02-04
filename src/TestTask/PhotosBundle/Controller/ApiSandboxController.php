<?php

namespace TestTask\PhotosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApiSandboxController extends Controller
{
    public function sandboxAction($api_method)
    {
        $apiConfig = array(
            'get_photos' => array(
                'uri' => '/photos',
                'method' => 'get',
                'fields' => array(
                    'page' => array(
                        'label' => 'Страница',
                    ),
                    'tags' => array(
                        'label' => 'Теги',
                        'multuple' => true,
                    )
                ),
            ),
            'delete_tags' => array(
                'uri' => '/tags',
                'method' => 'post',
                'fields' => array(
                    'tags' => array(
                        'label' => 'Теги',
                        'multuple' => true,
                        'required' => true,
                    )
                ),
            ),
            'delete_photo' => array(
                'uri' => '/photos/{id}',
                'method' => 'delete',
                'params' => array(
                    'id' => array(
                        'label' => 'Айди фото',
                        'required' => true,
                    )
                ),
            ),
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

        $apiConfig = array_map(function($config) {
            $config = array_merge(array(
                'params' => array(),
                'fields' => array(),
            ), $config);

            $config['method'] = mb_strtoupper($config['method']);

            return $config;
        }, $apiConfig);

        return $this->render('@TestTaskPhotos/Api/_api_sandbox_layout.html.twig', array('config' => $apiConfig[$api_method]));
    }
}
