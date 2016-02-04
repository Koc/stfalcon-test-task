<?php

namespace TestTask\PhotosBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use TestTask\TagsBundle\Entity\Tag;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="photo")
 * @ORM\Entity(repositoryClass="TestTask\PhotosBundle\Repository\PhotoRepository")
 *
 * @Serializer\ExclusionPolicy("ALL")
 * @Serializer\AccessorOrder("custom", custom = {"id", "title", "createdAt", "getTagsAsArray"})
 *
 * @Vich\Uploadable
 */
class Photo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose()
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Expose()
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="file_path", type="string", length=255)
     */
    private $filePath;

    /**
     * @var int
     *
     * @ORM\Column(name="file_size", type="integer")
     */
    private $fileSize;

    /**
     * @var string
     *
     * @ORM\Column(name="file_mime_type", type="string", length=20)
     */
    private $fileMimeType;

    /**
     * @var string
     *
     * @ORM\Column(name="file_original_name", type="string", length=255)
     *
     * @Serializer\Expose()
     * @Serializer\SerializedName("title")
     * @Serializer\Accessor(getter="getTitle")
     */
    private $fileOriginalName;

    /**
     * @ORM\OneToMany(targetEntity="PhotoTag", mappedBy="photo", cascade={"persist"}, orphanRemoval=true)
     *
     * @var ArrayCollection|PhotoTag[]
     */
    private $photoTags;

    /**
     * @Vich\UploadableField(mapping="photos", fileNameProperty="filePath")
     *
     * @var File
     */
    private $file;

    private $tagsAsArray;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->photoTags = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Photo
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set filePath
     *
     * @param string $filePath
     * @return Photo
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set fileSize
     *
     * @param integer $fileSize
     * @return Photo
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Get fileSize
     *
     * @return integer
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Set fileMimeType
     *
     * @param string $fileMimeType
     * @return Photo
     */
    public function setFileMimeType($fileMimeType)
    {
        $this->fileMimeType = $fileMimeType;

        return $this;
    }

    /**
     * Get fileMimeType
     *
     * @return string
     */
    public function getFileMimeType()
    {
        return $this->fileMimeType;
    }

    /**
     * Set fileOriginalName
     *
     * @param string $fileOriginalName
     * @return Photo
     */
    public function setFileOriginalName($fileOriginalName)
    {
        $this->fileOriginalName = $fileOriginalName;

        return $this;
    }

    /**
     * Get fileOriginalName
     *
     * @return string
     */
    public function getFileOriginalName()
    {
        return $this->fileOriginalName;
    }

    public function getTitle()
    {
        return pathinfo($this->fileOriginalName, PATHINFO_FILENAME);
    }

    public function addTag(Tag $tag)
    {
        $photoTag = new PhotoTag();
        $photoTag->setTag($tag);
        $photoTag->setPhoto($this);
        $this->photoTags->add($photoTag);
    }

    public function removeTag(Tag $tag)
    {
        //TODO: implement
    }

    public function getTags()
    {
        $tags = array();
        foreach ($this->photoTags as $photoTag) {
            $tags[] = $photoTag->getTag();
        }

        return $tags;
    }

    public function setTagsAsArray(array $tags)
    {
        $this->tagsAsArray = $tags;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("tags")
     *
     * @return array
     */
    public function getTagsAsArray()
    {
        if (null === $this->tagsAsArray) {
            throw new \LogicException('Call "PhotoRepository::attachTagsToPhotos" first.');
        }

        return $this->tagsAsArray;
    }

    /**
     * @return File|null
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;

        $this->fileMimeType = $this->file->getMimeType();
        $this->fileSize = $this->file->getSize();
        if ($this->file instanceof UploadedFile) {
            $this->fileOriginalName = $this->file->getClientOriginalName();
        }
//        dump($this);
    }

    public function setFileFixture($filePath, $originalName)
    {
//        var_dump(func_get_args());exit;

//        $file = tmpfile();
//        fwrite($file, file_get_contents($filePath));
//        $tmpFilePath = stream_get_meta_data($file)['uri'];
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'image-');
        copy($filePath, $tmpFilePath);

        $this->setFile(new UploadedFile($tmpFilePath, $originalName, null, null, null, true));
    }
}
