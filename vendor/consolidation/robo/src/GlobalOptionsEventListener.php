<?php
namespace Robo;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Robo\Contract\ConfigAwareInterface;
use Robo\Common\ConfigAwareTrait;

class GlobalOptionsEventListener implements EventSubscriberInterface, ConfigAwareInterface
{
    use ConfigAwareTrait;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [ConsoleEvents::COMMAND => 'handleCommandEvent'];
    }

    /**
     * Run all of our individual operations when a command event is received.
     */
    public function handleCommandEvent(ConsoleCommandEvent $event)
    {
        $this->setGlobalOptions($event);
        $this->setConfigurationValues($event);
    }

    /**
     * Before a Console command runs, examine the global
     * commandline options from the event Input, and set
     * configuration values as appropriate.
     *
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     */
    public function setGlobalOptions(ConsoleCommandEvent $event)
    {
        $config = $this->getConfig();
        $input = $event->getInput();
        $globalOptions = $config->getGlobalOptionDefaultValues();

        // Set any config value that has a defined global option (e.g. --simulate)
        foreach ($globalOptions as $option => $default) {
            $value = $input->hasOption($option) ? $input->getOption($option) : null;
            // Unfortunately, the `?:` operator does not differentate between `0` and `null`
            if (!isset($value)) {
                $value = $default;
            }
            $config->set($option, $value);
        }
    }

    /**
     * Examine the commandline --define / -D options, and apply the provided
     * values to the active configuration.
     *
     * @param \Symfony\Component\Console\Event\ConsoleCommandEvent $event
     */
    public function setConfigurationValues(ConsoleCommandEvent $event)
    {
        $config = $this->getConfig();
        $input = $event->getInput();

        // Also set any `-D config.key=value` options from the commandline.
        if ($input->hasOption('define')) {
            $configDefinitions = $input->getOption('define');
            foreach ($configDefinitions as $value) {
                list($key, $value) = $this->splitConfigKeyValue($value);
                $config->set($key, $value);
            }
        }
    }

    /**
     * Split up the key=value config setting into its component parts. If
     * the input string contains no '=' character, then the value will be 'true'.
     *
     * @param string $value
     * @return array
     */
    protected function splitConfigKeyValue($value)
    {
        $parts = explode('=', $value, 2);
        $parts[] = true;
        return $parts;
    }
}
