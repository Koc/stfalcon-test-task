<?php

namespace TestTask\PhotosBundle\VichUploader;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class DirectoryNamer implements DirectoryNamerInterface
{
    /**
     * {@inheritdoc}
     */
    public function directoryName($object, PropertyMapping $mapping)
    {
        $fileName = $mapping->getFileName($object);

        return substr($fileName, 0, 2);
    }
}
