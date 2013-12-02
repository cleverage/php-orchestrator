<?php

namespace CleverAge\Orchestrator\Ticketing\Model;

class TicketUpdate
{
    protected $author;
    protected $comment;
    protected $notify;
    /**
     * @var \DateTime
     */
    protected $updatedAt;
    /**
     * @var array
     */
    protected $updatedData;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getNotify()
    {
        return $this->notify;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getUpdatedData()
    {
        return $this->updatedData;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    public function setNotify($notify)
    {
        $this->notify = $notify;
        return $this;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function setUpdatedData(array $updatedData)
    {
        $this->updatedData = $updatedData;
        return $this;
    }
}
