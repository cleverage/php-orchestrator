<?php

namespace CleverAge\Orchestrator\Sources\Model;


class Commit
{
    protected $id;
    protected $message;
    /**
     * @var CommitAuthor
     */
    protected $author;

    /**
     * @var \DateTime
     */
    protected $authoredAt;

    public function getId()
    {
        return $this->id;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getAuthoredAt()
    {
        return $this->authoredAt;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function setAuthor(CommitAuthor $author)
    {
        $this->author = $author;
        return $this;
    }

    public function setAuthoredAt(\DateTime $authoredAt)
    {
        $this->authoredAt = $authoredAt;
        return $this;
    }
}
