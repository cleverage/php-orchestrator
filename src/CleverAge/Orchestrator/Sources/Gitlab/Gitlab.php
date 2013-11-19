<?php

namespace CleverAge\Orchestrator\Sources\Gitlab;

use Gitlab\Client;
use CleverAge\Orchestrator\Sources\SourceInterface;

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
     * @return array<CleverAge\Orchestrator\Sources\Model\Project>
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
     * @param int $id
     * @return CleverAge\Orchestrator\Sources\Model\Project
     */
    public function getProject($id)
    {
        $projectApi = $this->client->api('projects')->show($id);

        return empty($projectApi) ? null : Converters::convertProjectFromApi($projectApi);
    }
}
