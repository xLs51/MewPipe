<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UserBundle\Entity\UserRepository")
 *
 * @ExclusionPolicy("all")
 */
class User extends BaseUser
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
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255, nullable=true)
     *
     * @Expose
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     *
     * @Expose
     */
    private $lastName;

    /**
     * @var datetime
     *
     * @ORM\Column(name="birthday", type="datetime", nullable=true)
     *
     * @Expose
     */
    private $birthday;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Video", mappedBy="user", cascade={"remove"})
     */
    private $videos;

    /**
     * @var string
     *
     * @ORM\Column(name="openid", type="string", nullable=true)
     *
     * @Expose
     */
    protected $openid;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Add videos
     *
     * @param \AppBundle\Entity\Video $videos
     * @return User
     */
    public function addVideo(\AppBundle\Entity\Video $videos)
    {
        $this->videos[] = $videos;

        $videos->setUser($this);

        return $this;
    }

    /**
     * Remove videos
     *
     * @param \AppBundle\Entity\Video $videos
     */
    public function removeVideo(\AppBundle\Entity\Video $videos)
    {
        $this->videos->removeElement($videos);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Set openid
     *
     * @param integer $openid
     * @return User
     */
    public function setOpenid($openid)
    {
        $this->openid = $openid;

        return $this;
    }

    /**
     * Get openid
     *
     * @return integer
     */
    public function getOpenid()
    {
        return $this->openid;
    }
}
