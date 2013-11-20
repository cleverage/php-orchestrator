<?php

namespace CleverAge\Orchestrator\Sources\Model;

class CommitAuthor
{
    protected $name;
    protected $email;

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
}
