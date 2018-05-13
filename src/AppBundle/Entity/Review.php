<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use AppBundle\Entity\Place;
/**
 * Review
 *
 * @ORM\Table(name="review")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReviewRepository")
 */
class Review
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
     * @ORM\ManyToOne(targetEntity="Place")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id")
     */
    private $place;

    /**
     * @var string
     *
     * @ORM\Column(name="authorName", type="string", length=255)
     */
    private $authorName;

    /**
     * @var string
     *
     * @ORM\Column(name="authorUrl", type="string", length=500)
     */
    private $authorUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=50)
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="profilePhotoUrl", type="string", length=1000)
     */
    private $profilePhotoUrl;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="relativeTimeDescription", type="string", length=100)
     */
    private $relativeTimeDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=1000)
     */
    private $text;

    /**
     * @var int
     *
     * @ORM\Column(name="time", type="integer")
     */
    private $time;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set authorName
     *
     * @param string $authorName
     *
     * @return Review
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Get authorName
     *
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * Set authorUrl
     *
     * @param string $authorUrl
     *
     * @return Review
     */
    public function setAuthorUrl($authorUrl)
    {
        $this->authorUrl = $authorUrl;

        return $this;
    }

    /**
     * Get authorUrl
     *
     * @return string
     */
    public function getAuthorUrl()
    {
        return $this->authorUrl;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return Review
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set profilePhotoUrl
     *
     * @param string $profilePhotoUrl
     *
     * @return Review
     */
    public function setProfilePhotoUrl($profilePhotoUrl)
    {
        $this->profilePhotoUrl = $profilePhotoUrl;

        return $this;
    }

    /**
     * Get profilePhotoUrl
     *
     * @return string
     */
    public function getProfilePhotoUrl()
    {
        return $this->profilePhotoUrl;
    }

    /**
     * Set rating
     *
     * @param integer $rating
     *
     * @return Review
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set relativeTimeDescription
     *
     * @param string $relativeTimeDescription
     *
     * @return Review
     */
    public function setRelativeTimeDescription($relativeTimeDescription)
    {
        $this->relativeTimeDescription = $relativeTimeDescription;

        return $this;
    }

    /**
     * Get relativeTimeDescription
     *
     * @return string
     */
    public function getRelativeTimeDescription()
    {
        return $this->relativeTimeDescription;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Review
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param int $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }



    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }


}

