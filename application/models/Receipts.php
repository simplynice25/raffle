<?php

namespace models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity @Table(name="raffle_receipt")
 **/
class Receipts {

	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;

	/** @ManyToOne(targetEntity="Users") 
	 *  @JoinColumn(name="user_id", referencedColumnName="id")
	*/
	private $user;

	/** @Column(type="string", nullable=true) **/
	protected $receipt_number;

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
     * Set receipt_number
     *
     * @param string $receiptNumber
     * @return Receipt
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
     * Set view_status
     *
     * @param integer $viewStatus
     * @return Receipt
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
     * @return Receipt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = new \DateTime("now");
    
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
     * @return Receipt
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modified_at = new \DateTime("now");
    
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
     * @return Receipt
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
