<?php

namespace TestTask\PhotosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TestTask\TagsBundle\Entity\Tag;

/**
 * PhotoTag
 *
 * @ORM\Table(name="photo_tag",
 *     uniqueConstraints={
 *     @ORM\UniqueConstraint(name="UNIQ_photo_tag", columns={"photo_id", "tag_id"})
 * })
 * @ORM\Entity(repositoryClass="TestTask\PhotosBundle\Repository\PhotoTagRepository")
 */
class PhotoTag
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
     * @ORM\ManyToOne(targetEntity="Photo", inversedBy="photoTags")
     * @ORM\JoinColumn(name="photo_id", nullable=false, onDelete="CASCADE")
     *
     * @var Photo
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity="TestTask\TagsBundle\Entity\Tag")
     * @ORM\JoinColumn(name="tag_id", nullable=false, onDelete="CASCADE")
     *
     * @var Tag
     */
    private $tag;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

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
     * @return Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param Photo $photo
     */
    public function setPhoto(Photo $photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param Tag $tag
     */
    public function setTag(Tag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PhotoTag
     */
    public function setCreatedAt(\DateTime $createdAt)
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
}
