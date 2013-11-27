<?php

namespace CleverAge\Orchestrator;

use CleverAge\Orchestrator\Feature;

class Sorter
{
    /**
     * Sort features, depending of blocking tickets
     *
     * http://en.wikipedia.org/wiki/Topological_sorting
     *
     * @param array<CleverAge\Orchestrator\Feature> $features
     * @param array<mixed> $blockingStatus List of status considering ticket as blocker
     * @return array
     */
    public static function sortByBlockingTickets(array $features, array $blockingStatus)
    {
        $sorted = array();
        $keyedFeatures = array();

        foreach ($features as $feature) {
            $keyedFeatures[$feature->getTicket()->getId()] = $feature;
        }

        foreach ($keyedFeatures as $feature) {
            self::visitSortByBlockingTickets($feature, $sorted, $blockingStatus, $keyedFeatures);
        }

        return $sorted;
    }

    private static function visitSortByBlockingTickets(Feature $feature, array &$sorted, array $blockingStatus, array $features)
    {
        $t = $feature->getTicket();
        $isMainFeature = isset($features[$t->getId()]);

        if (!in_array($feature, $sorted)) {
            foreach ($t->getBlocking() as $blocking) {
                if ($blocking->isBlocking($blockingStatus)) {
                    $f = isset($features[$blocking->getId()]) ? $features[$blocking->getId()] : new Feature($blocking);
                    self::visitSortByBlockingTickets($f, $sorted, $blockingStatus, $features);
                }
            }

            if ($isMainFeature) {
                array_unshift($sorted, $feature);
            }
        }
    }
}
