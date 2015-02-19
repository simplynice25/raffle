<?php

namespace models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity @Table(name="raffle_encoded_receipts")
 **/
class EncodedReceipts {

	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;

	/** @ManyToOne(targetEntity="Raffles") 
	 *  @JoinColumn(name="raffle_id", referencedColumnName="id")
	*/
	private $raffle;

    /** @ManyToOne(targetEntity="Users") 
     *  @JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $user;

	/** @Column(type="string", nullable=true) **/
	protected $receipt_number;

    /** @Column(type="string", nullable=true) **/
    protected $full_name;

    /** @Column(type="string", nullable=true) **/
    protected $email;

	/** @Column(type="integer", nullable=true) **/
	protected $view_status; // 1 = Deleted, 2 = Used, 5 = Active

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
     * Set receipt_number
     *
     * @param string $receiptNumber
     * @return EncodedReceipts
     */
    public function setReceiptNumber($receiptNumber)
    {
        $this->receipt_number = $receiptNumber;
    
        return $this;
    }

    /**
     * Get receipt_number
     *
     * @return string 
     */
    public function getReceiptNumber()
    {
        return $this->receipt_number;
    }

    /**
     * Set full_name
     *
     * @param string $fullName
     * @return EncodedReceipts
     */
    public function setFullName($fullName)
    {
        $this->full_name = $fullName;
    
        return $this;
    }

    /**
     * Get full_name
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return EncodedReceipts
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set view_status
     *
     * @param integer $viewStatus
     * @return EncodedReceipts
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
     * @return EncodedReceipts
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
     * @return EncodedReceipts
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
     * @return EncodedReceipts
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
     * Set user
     *
     * @param \models\Users $user
     * @return EncodedReceipts
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
}
