<?php

namespace models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity @Table(name="raffle_consolations")
 **/
class Consolations {

	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;

	/** @ManyToOne(targetEntity="Raffles") 
	 *  @JoinColumn(name="raffle_id", referencedColumnName="id")
	*/
	private $raffle;

	/** @ManyToOne(targetEntity="Prizes") 
	 *  @JoinColumn(name="prize_id", referencedColumnName="id")
	*/
	private $prize;

	/** @Column(type="integer", nullable=true) **/
	protected $winner;

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
     * Set winner
     *
     * @param integer $winner
     * @return Winners
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;
    
        return $this;
    }

    /**
     * Get winner
     *
     * @return integer 
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * Set view_status
     *
     * @param integer $viewStatus
     * @return Winners
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
     * @return Winners
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
     * @return Winners
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
     * @return Winners
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

    /**
     * Set prize
     *
     * @param \models\Prizes $prize
     * @return Winners
     */
    public function setPrize(\models\Prizes $prize = null)
    {
        $this->prize = $prize;
    
        return $this;
    }

    /**
     * Get prize
     *
     * @return \models\Prizes 
     */
    public function getPrize()
    {
        return $this->prize;
    }
}
