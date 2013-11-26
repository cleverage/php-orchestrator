<?php

namespace CleverAge\Orchestrator\Sources\Gitlab;

use Gitlab\Client;
use Gitlab\Exception\RuntimeException;
use CleverAge\Orchestrator\Model\Urlisable;
use CleverAge\Orchestrator\Sources\CachedSource;
use CleverAge\Orchestrator\Sources\Model;

class Gitlab extends CachedSource
{
    /**
     * @var \Gitlab\Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getName()
    {
        return 'gitlab';
    }

    protected function getPage($limit, $offset)
    {
        return ((int) $offset/$limit)+1;
    }

    /**
     * @inheritdoc
     */
    public function getUrlFor(Urlisable $object)
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
    protected function doGetProjects(array $ids = array())
    {
        $projectsApi = $this->client->api('projects')->all();

        $projects = array();

        foreach ($projectsApi as $projectApi) {
            $project = Converters::convertProjectFromApi($projectApi);
            if (in_array($project->getId(), $ids)) {
                $projects[] = $project;
            }
        }

        return $projects;
    }

    /**
     * @inheritdoc
     */
    protected function doGetProject($id)
    {
        $projectApi = $this->client->api('projects')->show($id);

        return empty($projectApi) ? null : Converters::convertProjectFromApi($projectApi);
    }

    /**
     * @inheritdoc
     */
    protected function doGetBranch(Model\Project $project, $branch)
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
    protected function doGetMergeRequests(Model\Project $project, $limit = 20, $offset = 0)
    {
        $page = $this->getPage($limit, $offset);

        $mrsApi = $this->client->api('merge_requests')->all($project->getId(), $page, $limit);

        $mrs = array();

        foreach ($mrsApi as $mrApi) {
            $mr = Converters::convertMergeRequestFromApi($mrApi);
            $mr->setProject($project);
            $mrs[] = $mr;
        }

        return $mrs;
    }
}
