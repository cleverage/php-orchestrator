<?php

namespace CleverAge\Orchestrator\Sources;

interface ConverterInterface
{
    /**
     * @param mixed $projectApi
     * @return \CleverAge\Orchestrator\Sources\Model\Project
     */
    public function convertProjectFromApi($projectApi);

    /**
     * @param mixed $branchApi
     * @return \CleverAge\Orchestrator\Sources\Model\Branch
     */
    public function convertBranchFromApi($branchApi);

    /**
     * @param mixed $commitApi
     * @return \CleverAge\Orchestrator\Sources\Model\Commit
     */
    public function convertCommitFromApi($commitApi);

    /**
     * @param mixed $commitAuthorApi
     * @return \CleverAge\Orchestrator\Sources\Model\CommitAuthorInterface
     */
    public function convertCommitAuthorFromApi($commitAuthorApi);

    /**
     * @param mixed $mrApi
     * @return \CleverAge\Orchestrator\Sources\Model\MergeRequest
     */
    public function convertMergeRequestFromApi($mrApi);

    /**
     * @param mixed $user
     * @return \CleverAge\Orchestrator\Sources\Model\MergeRequestUserInterface
     */
    public function convertMergeRequestUserFromApi($user);

    /**
     * @param mixed $userApi
     * @return \CleverAge\Orchestrator\Sources\Model\User
     */
    public function convertUserFromApi($userApi);
}
