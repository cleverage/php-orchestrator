<?php

namespace CleverAge\Orchestrator;

use CleverAge\Orchestrator\Request\Request;
use CleverAge\Orchestrator\Sources\SourceInterface;
use CleverAge\Orchestrator\Ticketing\TicketingInterface;

class Orchestrator
{
    /**
     * @var CleverAge\Orchestrator\Source\SourceInterface
     */
    protected $source;

    /**
     * @var CleverAge\Orchestrator\Ticketing\TicketingInterface
     */
    protected $ticketing;

    /**
     * @var array<CleverAge\Orchestrator\Source\Model\Project>
     */
    protected $sourceProjects = array();

    public function __construct(TicketingInterface $ticketing = null, SourceInterface $source = null)
    {
        $this->ticketing = $ticketing;
        $this->source = $source;
    }

    public function setSourcesProjects(array $projects)
    {
        $this->sourceProjects = $projects;

        return $this;
    }

    public function getTicketWithSources(Request $request)
    {
        $tickets = $this->ticketing->getTicketListByStatus($request->getStatus());

        $features = array();

        foreach ($tickets as $ticket) {

            $sources = array();

            if ($this->source) {
                $sourceIdClosure = $request->getSourceIdClosure();
                foreach ($this->sourceProjects as $project) {
                    $sourceIds = $sourceIdClosure($ticket);
                    foreach ($sourceIds as $sourceId) {
                        $source = $this->source->getBranch($project, $sourceId);
                        if (!empty($source)) {
                            $sources[] = $source;
                        }
                    }
                }
            }

            $features[] = new Feature($ticket, $sources);
        }

        return $features;
    }
}
