<?php

namespace TestTask\PhotosBundle\VichUploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

class ShaNamer implements NamerInterface
{
    /**
     * {@inheritdoc}
     */
    public function name($object, PropertyMapping $mapping)
    {
        $file = $mapping->getFile($object);
        /* @var $file UploadedFile */
        $name = sha1(microtime(true).mt_rand(0, 999));

        if ($extension = $file->guessExtension()) {
            $name = sprintf('%s.%s', $name, $extension);
        }

        return $name;
    }
}
