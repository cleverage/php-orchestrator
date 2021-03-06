<?php

namespace CleverAge\Orchestrator\Sources\Gitlab;

use CleverAge\Orchestrator\Sources\Model;
use CleverAge\Orchestrator\Sources\ConverterInterface;

class Converter implements ConverterInterface
{
    public function convertProjectFromApi($projectApi)
    {
        if (!is_array($projectApi)) {
            return null;
        }

        $p = new Model\Project();
        $p
            ->setRaw($projectApi)
            ->setId($projectApi['id'])
            ->setName($projectApi['name'])
            ->setDescription($projectApi['description'])
            ->setUrl($projectApi['web_url'])
            ->setHttpGit($projectApi['http_url_to_repo'])
            ->setSshGit($projectApi['ssh_url_to_repo'])
        ;
        return $p;
    }

    public function convertBranchFromApi($branchApi)
    {
        if (!is_array($branchApi)) {
            return null;
        }

        $b = new Model\Branch();
        $b
            ->setRaw($branchApi)
            ->setName($branchApi['name'])
            ->setLastCommit($this->convertCommitFromApi($branchApi['commit']))
        ;
        return $b;
    }

    public function convertCommitFromApi($commitApi)
    {
        if (!is_array($commitApi)) {
            return null;
        }

        $c = new Model\Commit();
        $c
            ->setId($commitApi['id'])
            ->setMessage($commitApi['message'])
            ->setAuthoredAt(new \DateTime($commitApi['authored_date']))
            ->setCommittedAt(new \DateTime($commitApi['committed_date']))
        ;

        if (isset($commitApi['author_name'])) {
            $c->setAuthor($this->convertCommitAuthorFromApi([
                'name' => $commitApi['author_name'],
                'email' => $commitApi['author_email'],
            ]));
        }

        if (isset($commitApi['committer_name'])) {
            $c->setCommitter($this->convertCommitAuthorFromApi([
                'name' => $commitApi['committer_name'],
                'email' => $commitApi['committer_email'],
            ]));
        }

        return $c;
    }

    public function convertCommitAuthorFromApi($commitAuthorApi)
    {
        if (!is_array($commitAuthorApi)) {
            return null;
        }

        $a = new Model\User();
        $a
            ->setName($commitAuthorApi['name'])
            ->setEmail($commitAuthorApi['email'])
        ;
        return $a;
    }

    public function convertMergeRequestFromApi($mrApi)
    {
        if (!is_array($mrApi)) {
            return null;
        }

        $mr = new Model\MergeRequest();

        $state = Model\MergeRequest::STATE_OPENED;
        switch ($mrApi['state']) {
            case 'merged': $state = Model\MergeRequest::STATE_MERGED; break;
            case 'closed': $state = Model\MergeRequest::STATE_CLOSED; break;
        }

        $mr
            ->setRaw($mrApi)
            ->setId($mrApi['iid'])
            ->setGlobalId($mrApi['id'])
            ->setName($mrApi['title'])
            ->setSourceBranchName($mrApi['source_branch'])
            ->setTargetBranchName($mrApi['target_branch'])
            ->setState($state)
            ->setAuthor($this->convertMergeRequestUserFromApi($mrApi['author']))
        ;

        if (!empty($mrApi['assignee'])) {
            $mr->setAssignee($this->convertMergeRequestUserFromApi($mrApi['assignee']));
        }

        return $mr;
    }

    public function convertMergeRequestUserFromApi($user)
    {
        if (!is_array($user)) {
            return null;
        }

        $u = new Model\User();
        $u
            ->setId($user['id'])
            ->setUsername($user['username'])
            ->setName($user['name'])
        ;

        if (array_key_exists('created_at', $user)) {
            $u->setCreatedAt(new \DateTime($user['created_at']));
        }

        if (array_key_exists('email', $user)) {
            $u->setEmail($user['email']);
        }

        return $u;
    }

    public function convertUserFromApi($userApi)
    {
        if (!is_array($userApi)) {
            return null;
        }

        $u = new Model\User();
        $u
            ->setId($userApi['id'])
            ->setUsername($userApi['username'])
            ->setName($userApi['name'])
            ->setIsEnabled($userApi['state'] === 'active')
        ;

        // for admins
        if (array_key_exists('email', $userApi)) {
            $u
                ->setCreatedAt(new \DateTime($userApi['created_at']))
                ->setEmail($userApi['email'])
                ->setSkype($userApi['skype'])
                ->setLinkedin($userApi['linkedin'])
                ->setTwitter($userApi['twitter'])
            ;
        }

        return $u;
    }
}
