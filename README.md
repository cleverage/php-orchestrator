php-orchestrator
================

# Events

On service request :

* Pre fetch
* Post fetch
* Exception, which can be silented.

See the embedded Listeners.

Here is a possible implementation for Symfony2 :

    <parameters>
        <parameter key="orchestrator.event.service.fetch.pre" type="constant">CleverAge\Orchestrator\Events\OrchestratorEvents::SERVICE_FETCH_PRE</parameter>
        <parameter key="orchestrator.event.service.fetch.post" type="constant">CleverAge\Orchestrator\Events\OrchestratorEvents::SERVICE_FETCH_POST</parameter>
        <parameter key="orchestrator.event.service.fetch.error" type="constant">CleverAge\Orchestrator\Events\OrchestratorEvents::SERVICE_FETCH_ERROR</parameter>
    </parameters>

    <services>
        <service id="orchestrator.listeners.cache" class="CleverAge\Orchestrator\Service\Listeners\CacheListener">
            <tag name="kernel.event_listener" event="%orchestrator.event.service.fetch.pre" method="onServicePreFetch" priority="255"/>
            <tag name="kernel.event_listener" event="%orchestrator.event.service.fetch.post%" method="onServicePostFetch" priority="-255"/>
            <argument type="service" id="some.cache.service" />
        </service>
    </services>


#Run tests

    php composer.phar install --dev
    vendor/bin/atoum -bf .atoum.bootstrap.php -d tests