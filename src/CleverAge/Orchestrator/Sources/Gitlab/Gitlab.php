<?php

namespace CleverAge\Orchestrator\Sources\Gitlab;

use Gitlab\Client;
use Gitlab\Exception\RuntimeException;
use CleverAge\Orchestrator\Sources\SourceInterface;
use CleverAge\Orchestrator\Sources\Model;

class Gitlab implements SourceInterface
{
    /**
     * @var \Gitlab\Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function getUrlFor($object)
    {
        if ($object instanceof Model\Project) {
            return $object->getUrl();
        } elseif ($object instanceof Model\Branch) {
            return $object->getProject()->getUrl().'/commits/'.$object->getName();
        } elseif ($object instanceof Model\MergeRequest) {
            return $object->getProject()->getUrl().'/merge_requests/'.$object->getId();
        }

        return substr($this->client->getBaseUrl(), 0, strpos($this->client->getBaseUrl(), '/api/')).'/';
    }

    /**
     * @inheritdoc
     */
    public function getProjects()
    {
        $projectsApi = $this->client->api('projects')->all();

        $projects = array();

        foreach ($projectsApi as $projectApi) {
            $projects[] = Converters::convertProjectFromApi($projectApi);
        }

        return $projects;
    }

    /**
     * @inheritdoc
     */
    public function getProject($id)
    {
        $projectApi = $this->client->api('projects')->show($id);

        return empty($projectApi) ? null : Converters::convertProjectFromApi($projectApi);
    }

    /**
     * @inheritdoc
     */
    public function getBranch(Model\Project $project, $branch)
    {
        try {
            $branchApi = $this->client->api('repositories')->branch($project->getId(), $branch);
        } catch (RuntimeException $e) {
            if ($e->getCode() == 404) {
                return null;
            }
            throw $e;
        }

        $branch = Converters::convertBranchFromApi($branchApi);
        $branch->setProject($project);

        return $branch;
    }

    /**
     * @inheritdoc
     */
    public function getMergeRequests(Model\Project $project, $page = 1, $perPage = 20)
    {
        $mrsApi = $this->client->api('merge_requests')->all($project->getId(), $page, $perPage);

        $mrs = array();

        foreach ($mrsApi as $mrApi) {
            $mr = Converters::convertMergeRequestFromApi($mrApi);
            $mr->setProject($project);
            $mrs[] = $mr;
        }

        return $mrs;
    }
}
