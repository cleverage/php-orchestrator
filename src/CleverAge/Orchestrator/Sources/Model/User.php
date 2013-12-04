<?php

namespace CleverAge\Orchestrator\Sources\Model;

class User implements MergeRequestUserInterface, CommitAuthorInterface
{
    protected $id;
    protected $username;
    protected $email;
    protected $name;
    /**
     * @var boolean
     */
    protected $isEnabled;
    /**
     * @var \DateTime
     */
    protected $createdAt;
    protected $skype;
    protected $linkedin;
    protected $twitter;

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getSkype()
    {
        return $this->skype;
    }

    public function getLinkedin()
    {
        return $this->linkedin;
    }

    public function getTwitter()
    {
        return $this->twitter;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setSkype($skype)
    {
        $this->skype = $skype;
        return $this;
    }

    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;
        return $this;
    }

    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
        return $this;
    }
}
