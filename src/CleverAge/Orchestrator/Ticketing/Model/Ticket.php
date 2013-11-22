<?php

namespace CleverAge\Orchestrator\Ticketing\Model;

use CleverAge\Orchestrator\Model\Urlisable;

class Ticket implements Urlisable
{
    protected $id;
    protected $title;
    protected $description;
    protected $status;
    protected $reporter;
    protected $milestone;
    protected $type;
    protected $owner;
    protected $priority;
    protected $url;

    /**
     * @var array
     */
    protected $blocking = array();

    /**
     * @var array
     */
    protected $blockedBy = array();

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getReporter()
    {
        return $this->reporter;
    }

    public function getMilestone()
    {
        return $this->milestone;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function getBlocking()
    {
        return $this->blocking;
    }

    public function getBlockedBy()
    {
        return $this->blockedBy;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setReporter($reporter)
    {
        $this->reporter = $reporter;
        return $this;
    }

    public function setMilestone($milestone)
    {
        $this->milestone = $milestone;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    public function setBlocking(array $blocking)
    {
        $this->blocking = $blocking;
        return $this;
    }

    public function setBlockedBy(array $blockedBy)
    {
        $this->blockedBy = $blockedBy;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
}
