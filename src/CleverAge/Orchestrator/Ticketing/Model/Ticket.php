<?php

namespace CleverAge\Orchestrator\Ticketing\Model;

use CleverAge\Orchestrator\Model\Urlisable;
use CleverAge\Orchestrator\Model\RawData;

class Ticket extends RawData implements Urlisable
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
    protected $closed;
    protected $keywords = array();

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

    public function isClosed()
    {
        return $this->closed;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function isOpened()
    {
        return !$this->isClosed();
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

    public function setIsClosed($closed)
    {
        $this->closed = $closed;
        return $this;
    }

    public function setKeywords(array $keywords)
    {
        $this->keywords = $keywords;
        return $this;
    }

    public function addKeyword($keyword)
    {
        $keyword = $this->sanitizeKeyword($keyword);

        if (in_array($keyword, $this->keywords)) {
            $this->keywords[] = $keyword;
        }

        return $this;
    }

    public function removeKeyword($keyword)
    {
        $keyword = $this->sanitizeKeyword($keyword);

        if ($pos = array_search($keyword, $this->keywords)) {
            unset($this->keywords[$pos]);
        }

        return $this;
    }

    protected function sanitizeKeyword($keyword)
    {
        return trim($keyword);
    }

    /**
     * @param array $blockingStatus
     * @return boolean
     */
    public function hasBlocking(array $blockingStatus)
    {
        foreach ($this->blocking as $blocking) {
            if ($blocking->hasBlockingStatus($blockingStatus) || $blocking->isBlocking($blockingStatus)) {
                return true;
            }
        }

        return false;
    }

    public function hasBlockingStatus(array $blockingStatus)
    {
        return in_array($this->getStatus(), $blockingStatus);
    }

    /**
     * @param array $blockingStatus
     * @return boolean
     */
    public function isBlocking(array $blockingStatus)
    {
        if (!$this->hasBlockingStatus($blockingStatus)) {
            return false;
        }

        return $this->hasBlocking($blockingStatus);
    }

    /**
     * @param array $blockingStatus
     * @return boolean
     */
    public function isBlocked(array $blockingStatus)
    {
        foreach ($this->getBlockedBy() as $blockedBy) {
            if ($blockedBy->hasBlockingStatus($blockingStatus) || $blockedBy->isBlocked($blockingStatus)) {
                return true;
            }
        }

        return false;
    }
}
