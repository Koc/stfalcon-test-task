<?php

namespace TestTask\PhotosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="photo")
 * @ORM\Entity(repositoryClass="TestTask\PhotosBundle\Repository\PhotoRepository")
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
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
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
     */
    private $fileOriginalName;

    /**
     * @Vich\UploadableField(mapping="photos", fileNameProperty="filePath")
     *
     * @var File
     */
    private $file;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
