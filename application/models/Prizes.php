<?php

namespace models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity @Table(name="raffle_prizes")
 **/
class Prizes {

	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;

	/** @ManyToOne(targetEntity="Raffles") 
	 *  @JoinColumn(name="raffle_id", referencedColumnName="id")
	*/
	private $raffle;

	/** @Column(type="integer", nullable=true) **/
	protected $prize_place;

	/** @Column(type="string", nullable=true) **/
	protected $prize_title;

	/** @Column(type="string", nullable=true) **/
	protected $prize_description;

	/** @Column(type="integer", nullable=true) **/
	protected $prize_amount;

	/** @Column(type="string", nullable=true) **/
	protected $prize_image;

	/** @Column(type="integer", nullable=true) **/
	protected $prize_type;

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
     * Set prize_place
     *
     * @param integer $prizePlace
     * @return Prizes
     */
    public function setPrizePlace($prizePlace)
    {
        $this->prize_place = $prizePlace;
    
        return $this;
    }

    /**
     * Get prize_place
     *
     * @return integer 
     */
    public function getPrizePlace()
    {
        return $this->prize_place;
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
     * Set prize_description
     *
     * @param string $prizeDescription
     * @return Prizes
     */
    public function setPrizeDescription($prizeDescription)
    {
        $this->prize_description = $prizeDescription;
    
        return $this;
    }

    /**
     * Get prize_description
     *
     * @return string 
     */
    public function getPrizeDescription()
    {
        return $this->prize_description;
    }

    /**
     * Set prize_amount
     *
     * @param integer $prizeAmount
     * @return Prizes
     */
    public function setPrizeAmount($prizeAmount)
    {
        $this->prize_amount = $prizeAmount;
    
        return $this;
    }

    /**
     * Get prize_amount
     *
     * @return integer 
     */
    public function getPrizeAmount()
    {
        return $this->prize_amount;
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
     * Set prize_type
     *
     * @param integer $prizeType
     * @return Prizes
     */
    public function setPrizeType($prizeType)
    {
        $this->prize_type = $prizeType;
    
        return $this;
    }

    /**
     * Get prize_type
     *
     * @return integer 
     */
    public function getPrizeType()
    {
        return $this->prize_type;
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

    /**
     * Set raffle
     *
     * @param \models\Raffles $raffle
     * @return Prizes
     */
    public function setRaffle(\models\Raffles $raffle = null)
    {
        $this->raffle = $raffle;
    
        return $this;
    }

    /**
     * Get raffle
     *
     * @return \models\Raffles 
     */
    public function getRaffle()
    {
        return $this->raffle;
    }
}
