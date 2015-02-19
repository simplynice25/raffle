<?php

namespace models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity @Table(name="raffle_winners_archive")
 **/
class WinnersArchive
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

    /** @ManyToOne(targetEntity="Users") 
     *  @JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;

    /** @ManyToOne(targetEntity="Raffles") 
     *  @JoinColumn(name="raffle_id", referencedColumnName="id")
    */
    private $raffle;

    /** @Column(type="integer", nullable=true) **/
    protected $order_number;

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
     * Set order_number
     *
     * @param integer $order_number
     * @return WinnersArchive
     */
    public function setOrderNumber($order_number)
    {
        $this->order_number = $order_number;
    
        return $this;
    }

    /**
     * Get order_number
     *
     * @return integer 
     */
    public function getOrderNumber()
    {
        return $this->order_number;
    }

    /**
     * Set view_status
     *
     * @param integer $viewStatus
     * @return WinnersArchive
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
     * @return WinnersArchive
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
     * @return WinnersArchive
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
     * Set user
     *
     * @param \models\Users $user
     * @return WinnersArchive
     */
    public function setUser(\models\Users $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \models\Users 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set raffle
     *
     * @param \models\Raffles $raffle
     * @return WinnersArchive
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