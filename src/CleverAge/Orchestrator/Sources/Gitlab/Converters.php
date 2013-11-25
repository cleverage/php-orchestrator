<?php

namespace CleverAge\Orchestrator\Sources\Gitlab;

use CleverAge\Orchestrator\Sources\Model;

class Converters
{
    public static function convertProjectFromApi(array $projectApi)
    {
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

    public static function convertBranchFromApi(array $branchApi)
    {
        $b = new Model\Branch();
        $b
            ->setRaw($branchApi)
            ->setName($branchApi['name'])
            ->setLastCommit(self::convertCommitFromApi($branchApi['commit']))
        ;
        return $b;
    }

    public static function convertCommitFromApi(array $commitApi)
    {
        $c = new Model\Commit();
        $c
            ->setId($commitApi['id'])
            ->setMessage($commitApi['message'])
            ->setAuthor(self::convertCommitAuthorFromApi($commitApi['author']))
            ->setAuthoredAt(new \DateTime($commitApi['authored_date']))
        ;
        return $c;
    }

    public static function convertCommitAuthorFromApi(array $commitAuthorApi)
    {
        $a = new Model\CommitAuthor();
        $a
            ->setName($commitAuthorApi['name'])
            ->setEmail($commitAuthorApi['email'])
        ;
        return $a;
    }

    public static function convertMergeRequestFromApi(array $mrApi)
    {
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
            ->setAuthor(self::convertMergeRequestUserFromApi($mrApi['author']))
            ->setAssignee(self::convertMergeRequestUserFromApi($mrApi['assignee']))
        ;

        return $mr;
    }

    public static function convertMergeRequestUserFromApi(array $user)
    {
        $u = new Model\MergeRequestUser();
        $u
            ->setId($user['id'])
            ->setUsername($user['username'])
            ->setName($user['name'])
            ->setEmail($user['email'])
            ->setCreatedAt(new \DateTime($user['created_at']))
        ;
        return $u;
    }
}
