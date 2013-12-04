<?php

namespace CleverAge\Orchestrator\Sources\Model;

interface MergeRequestUserInterface
{
    public function getName();

    public function getEmail();

    public function getUsername();

    public function getId();

    public function getCreatedAt();

    public function setName($name);

    public function setEmail($email);

    public function setUsername($username);

    public function setId($id);

    public function setCreatedAt(\DateTime $createdAt);
}
