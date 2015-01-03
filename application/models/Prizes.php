<?php

namespace models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity @Table(name="raffle_prizes")
 **/
class Prizes {

	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;

	/** @Column(type="string", nullable=true) **/
	protected $prize_title;

	/** @Column(type="string", nullable=true) **/
	protected $prize_desc;

	/** @Column(type="string", nullable=true) **/
	protected $prize_image;

	/** @Column(type="integer", nullable=true) **/
	protected $view_status;

	/** @Column(type="datetime", nullable=true) **/
	protected $created_at;

	/** @Column(type="datetime", nullable=true) **/
	protected $modified_at;

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
     * Set prize_title
     *
     * @param string $prizeTitle
     * @return Prizes
     */
    public function setPrizeTitle($prizeTitle)
    {
        $this->prize_title = $prizeTitle;
    
        return $this;
    }

    /**
     * Get prize_title
     *
     * @return string 
     */
    public function getPrizeTitle()
    {
        return $this->prize_title;
    }

    /**
     * Set prize_desc
     *
     * @param string $prizeDesc
     * @return Prizes
     */
    public function setPrizeDesc($prizeDesc)
    {
        $this->prize_desc = $prizeDesc;
    
        return $this;
    }

    /**
     * Get prize_desc
     *
     * @return string 
     */
    public function getPrizeDesc()
    {
        return $this->prize_desc;
    }

    /**
     * Set prize_image
     *
     * @param string $prizeImage
     * @return Prizes
     */
    public function setPrizeImage($prizeImage)
    {
        $this->prize_image = $prizeImage;
    
        return $this;
    }

    /**
     * Get prize_image
     *
     * @return string 
     */
    public function getPrizeImage()
    {
        return $this->prize_image;
    }

    /**
     * Set view_status
     *
     * @param integer $viewStatus
     * @return Prizes
     */
    public function setViewStatus($viewStatus)
    {
        $this->view_status = $viewStatus;
    
        return $this;
    }

    /**
     * Get view_status
     *
     * @return integer 
     */
    public function getViewStatus()
    {
        return $this->view_status;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Prizes
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = new \DateTime('now');
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set modified_at
     *
     * @param \DateTime $modifiedAt
     * @return Prizes
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modified_at = new \DateTime('now');
    
        return $this;
    }

    /**
     * Get modified_at
     *
     * @return \DateTime 
     */
    public function getModifiedAt()
    {
        return $this->modified_at;
    }
}
