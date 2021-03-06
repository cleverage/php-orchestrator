<?php

namespace CleverAge\Orchestrator;

use CleverAge\Orchestrator\Request\Request;
use CleverAge\Orchestrator\Sources\SourceInterface;
use CleverAge\Orchestrator\Ticketing\TicketingInterface;
use CleverAge\Orchestrator\Ticketing\Model\Ticket;
use CleverAge\Orchestrator\Feature;

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

    public function fetchSourcesProjects(array $ids)
    {
        if ($this->source && !empty($ids)) {
            $this->setSourcesProjects(
                $this->source->getProjects($ids)
            );
        }

        return $this;
    }

    public function setSourcesProjects(array $projects)
    {
        $this->sourceProjects = $projects;

        return $this;
    }

    /**
     * @return array<CleverAge\Orchestrator\Source\Model\Project>
     */
    public function getSourcesProjects()
    {
        return $this->sourceProjects;
    }

    /**
     * @param \CleverAge\Orchestrator\Ticketing\Model\Ticket $ticket
     * @param \CleverAge\Orchestrator\Request\Request $request
     * @return \CleverAge\Orchestrator\Feature
     */
    protected function buildFeature(Ticket $ticket, Request $request)
    {
        $sources = array();

        if ($this->source) {
            $sourceIdClosure = $request->getSourceIdClosure();
            foreach ($this->sourceProjects as $project) {
                $sourceIds = $sourceIdClosure($ticket);
                foreach ($sourceIds as $sourceId) {
                    try {
                        $source = $this->source->getBranch($project, $sourceId);
                        if (!empty($source)) {
                            $sources[] = $source;
                        }
                    } catch(\RuntimeException $e) {
                        // probably 404, branch does not exists for project
                        // @todo add log
                    }
                }
            }
        }

        return new Feature($ticket, $sources);
    }

    /**
     * @param \CleverAge\Orchestrator\Request\Request $request
     * @return array<\CleverAge\Orchestrator\Feature>
     */
    public function getFeatures(Request $request)
    {
        $tickets = $this->ticketing->getTicketList($request);

        $features = array();

        foreach ($tickets as $ticket) {
            $features[] = $this->buildFeature($ticket, $request);
        }

        return $features;
    }

    /**
     * @param \CleverAge\Orchestrator\Request\Request $request
     * @return \CleverAge\Orchestrator\Feature|null
     */
    public function getFeature(Request $request)
    {
        $ticket = null;

        if ($request->getTicketId()) {
            $ticket = $this->ticketing->getTicketById($request->getTicketId());
        }

        if ($ticket) {
            return $this->buildFeature($ticket, $request);
        }

        return null;
    }

    /**
     * @param boolean|null $completed
     * @return array<CleverAge\Orchestrator\Ticketing\Model\Milestone>
     */
    public function getMilestones($completed = null)
    {
        return $this->ticketing->getMilestones($completed);
    }
}
