<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Video
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\VideoRepository")
 *
 * @ExclusionPolicy("all")
 */
class Video
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    private $id;

    /**
     * @Assert\File(maxSize="524288000")
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @Expose
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     *
     * @Expose
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="viewCount", type="integer")
     *
     * @Expose
     */
    private $viewCount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="shareCount", type="integer")
     *
     * @Expose
     */
    private $shareCount = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="shareUrl", type="text", nullable=true)
     *
     * @Expose
     */
    private $shareUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="addedDate", type="datetime")
     *
     * @Expose
     */
    private $addedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnailUrl", type="string", length=255, nullable=true)
     *
     * @Expose
     */
    private $thumbnailUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     *
     * @Expose
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="videos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     *
     * @Expose
     */
    private $path;

    /**
     * @ORM\Column(name="hash", type="string", length=255, nullable=true)
     *
     * @Expose
     */
    private $hash;

    /**
     * @VirtualProperty
     * @SerializedName("user_id")
     */
    public function getUserId()
    {
        return $this->user->getId();
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    public function getImageAbsolutePath()
    {
        return null === $this->thumbnailUrl
            ? null
            : $this->getUploadRootDir().'/'.$this->thumbnailUrl;
    }

    public function getImageWebPath()
    {
        return null === $this->thumbnailUrl
            ? null
            : $this->getUploadDir().'/'.$this->thumbnailUrl;
    }

    public function getUploadRootDir()
    {
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/videos';
    }

    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $this->path = $this->getId().".".$this->getFile()->getClientOriginalExtension();
        $this->thumbnailUrl = $this->getId().".png";

        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->path
        );

        return true;
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
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Video
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Video
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Video
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set viewCount
     *
     * @param integer $viewCount
     * @return Video
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    /**
     * Get viewCount
     *
     * @return integer
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * Set shareCount
     *
     * @param integer $shareCount
     * @return Video
     */
    public function setShareCount($shareCount)
    {
        $this->shareCount = $shareCount;

        return $this;
    }

    /**
     * Get shareCount
     *
     * @return integer
     */
    public function getShareCount()
    {
        return $this->shareCount;
    }

    /**
     * Set shareUrl
     *
     * @param string $shareUrl
     * @return Video
     */
    public function setShareUrl($shareUrl)
    {
        $this->shareUrl = $shareUrl;

        return $this;
    }

    /**
     * Get shareUrl
     *
     * @return string
     */
    public function getShareUrl()
    {
        return $this->shareUrl;
    }

    /**
     * Set addedDate
     *
     * @param \DateTime $addedDate
     * @return Video
     */
    public function setAddedDate($addedDate)
    {
        $this->addedDate = $addedDate;

        return $this;
    }

    /**
     * Get addedDate
     *
     * @return \DateTime
     */
    public function getAddedDate()
    {
        return $this->addedDate;
    }

    /**
     * Set thumbmailUrl
     *
     * @param string $thumbmailUrl
     * @return Video
     */
    public function setThumbmailUrl($thumbmailUrl)
    {
        $this->thumbmailUrl = $thumbmailUrl;

        return $this;
    }

    /**
     * Get thumbmailUrl
     *
     * @return string
     */
    public function getThumbmailUrl()
    {
        return $this->thumbmailUrl;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Video
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Video
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     * @return Video
     */
    public function setUser(\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function __construct()
    {
        $this->addedDate = new \Datetime();
    }
}
