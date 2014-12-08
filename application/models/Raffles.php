<?php

namespace models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity @Table(name="raffle_raffles")
 **/
class Raffles {

	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;

	/** @Column(type="string", nullable=true) **/
	protected $raffle_title;

	/** @Column(type="datetime", nullable=true) **/
	protected $start_date;

	/** @Column(type="datetime", nullable=true) **/
	protected $end_date;

	/** @Column(type="integer", nullable=true) **/
	protected $winners;

	/** @Column(type="integer", nullable=true) **/
	protected $consolations;

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
     * Set raffle_title
     *
     * @param string $raffleTitle
     * @return Raffles
     */
    public function setRaffleTitle($raffleTitle)
    {
        $this->raffle_title = $raffleTitle;
    
        return $this;
    }

    /**
     * Get raffle_title
     *
     * @return string 
     */
    public function getRaffleTitle()
    {
        return $this->raffle_title;
    }

    /**
     * Set start_date
     *
     * @param \DateTime $startDate
     * @return Raffles
     */
    public function setStartDate($startDate)
    {
        $this->start_date = $startDate;
    
        return $this;
    }

    /**
     * Get start_date
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set end_date
     *
     * @param \DateTime $endDate
     * @return Raffles
     */
    public function setEndDate($endDate)
    {
        $this->end_date = $endDate;
    
        return $this;
    }

    /**
     * Get end_date
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Set winners
     *
     * @param integer $winners
     * @return Raffles
     */
    public function setWinners($winners)
    {
        $this->winners = $winners;
    
        return $this;
    }

    /**
     * Get winners
     *
     * @return integer 
     */
    public function getWinners()
    {
        return $this->winners;
    }

    /**
     * Set consolations
     *
     * @param integer $consolations
     * @return Raffles
     */
    public function setConsolations($consolations)
    {
        $this->consolations = $consolations;
    
        return $this;
    }

    /**
     * Get consolations
     *
     * @return integer 
     */
    public function getConsolations()
    {
        return $this->consolations;
    }

    /**
     * Set view_status
     *
     * @param integer $viewStatus
     * @return Raffles
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
     * @return Raffles
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
     * @return Raffles
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
