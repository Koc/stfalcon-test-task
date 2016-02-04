<?php

namespace TestTask\PhotosBundle\Tests\Controller;

use Helmich\JsonAssert\JsonAssertions;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase
{
    use JsonAssertions;

    public function testPostInvalidPhoto()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/photos',
            array(
                'tags' => array(
                    'ololo',
                    'trololo',
                )
            ),
            array('image' => $this->getFile(__FILE__)),
            array('HTTP_Accept' => 'application/json')
        );

        $this->assertStatusCode(Response::HTTP_BAD_REQUEST, $client);
    }

    public function testPostPhoto()
    {
        $client = static::createClient();

        $tags = array(
            'happiness',
            'jump',
            'swimsuit',
        );

        $client->request(
            'POST',
            '/photos',
            array(
                'tags' => $tags
            ),
            array('image' => $this->getFile(__DIR__.'/../fixtures/happy_people-1000.jpg')),
            array('HTTP_Accept' => 'application/json')
        );

        $this->assertStatusCode(Response::HTTP_OK, $client);
        $this->assertJsonValueEquals($client->getResponse()->getContent(), 'photo.tags', $tags);
    }

    public function testGetPhotosByTag()
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/photos',
            array(
                'tags' => array('Thai')
            ),
            array(),
            array('HTTP_Accept' => 'application/json')
        );

        $this->assertStatusCode(Response::HTTP_OK, $client);
        $this->assertAllJsonValuesMatch($client->getResponse()->getContent(), 'photos..tags', \PHPUnit_Framework_Assert::contains('Thai'));
    }

    protected function getFile($path, $name = null)
    {
        $tmpfile = tempnam(sys_get_temp_dir(), 'sf_test_').basename($path);
        copy($path, $tmpfile);

        return new UploadedFile($tmpfile, $name ?: basename($path), null, null, null, true);
    }
}
