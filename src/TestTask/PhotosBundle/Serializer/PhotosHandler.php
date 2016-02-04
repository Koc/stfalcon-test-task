<?php

namespace TestTask\PhotosBundle\Serializer;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use TestTask\PhotosBundle\Entity\Photo;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class PhotosHandler implements EventSubscriberInterface
{
    private $uploaderHelper;

    private $cacheManager;

    public function __construct(UploaderHelper $uploaderHelper, CacheManager $cacheManager)
    {
        $this->uploaderHelper = $uploaderHelper;
        $this->cacheManager = $cacheManager;
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        $object = $event->getObject();
        $visitor = $event->getVisitor();
        /* @var $visitor GenericSerializationVisitor */

        $path = $this->uploaderHelper->asset($object, 'file');

        $urls = array();
        foreach (array('sq100', 'sq200', '400x225', '800x450') as $pattern) {
            $urls[$pattern] = $this->cacheManager->getBrowserPath($path, sprintf('photos_%s', $pattern));
        }

        $visitor->addData('image_urls', $urls);
    }

    public static function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.post_serialize', 'method' => 'onPostSerialize', 'class' => Photo::class),
        );
    }
}
