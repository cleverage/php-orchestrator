<?php

namespace CleverAge\Orchestrator\Sources\Model;


class Commit
{
    protected $id;
    protected $message;
    /**
     * @var CommitAuthorInterface
     */
    protected $author;

    /**
     * @var \DateTime
     */
    protected $authoredAt;

    /**
     * @var CommitAuthorInterface
     */
    protected $committer;

    /**
     * @var \DateTime
     */
    protected $committedAt;

    public function getId()
    {
        return $this->id;
    }

    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return CommitAuthorInterface
     */
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

    public function setAuthor(CommitAuthorInterface $author)
    {
        $this->author = $author;
        return $this;
    }

    public function setAuthoredAt(\DateTime $authoredAt)
    {
        $this->authoredAt = $authoredAt;
        return $this;
    }

    public function setCommitter(CommitAuthorInterface $committer)
    {
        $this->committer = $committer;
        return $this;
    }

    public function setCommittedAt(\DateTime $committedAt)
    {
        $this->committedAt = $committedAt;
        return $this;
    }
}
