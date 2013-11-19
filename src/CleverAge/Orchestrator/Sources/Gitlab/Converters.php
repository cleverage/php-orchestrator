<?php

namespace CleverAge\Orchestrator\Sources\Gitlab;

use CleverAge\Orchestrator\Sources\Model\Project;

class Converters
{
    static public function convertProjectFromApi($projectApi)
    {
        $p = new Project();
        $p
            ->setId($projectApi['id'])
            ->setName($projectApi['name'])
            ->setDescription($projectApi['description'])
            ->setUrl($projectApi['web_url'])
            ->setHttpGit($projectApi['http_url_to_repo'])
            ->setSshGit($projectApi['ssh_url_to_repo'])
        ;
        return $p;
    }
}
