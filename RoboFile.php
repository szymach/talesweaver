<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    public function build()
    {
        $this->taskWatch()
            ->monitor('assets/scss/base.scss', function () {
                $this->buildSass();
            })
            ->run()
        ;
    }
    
    private function buildSass()
    {
        $this->say("Starting SASS rebuild");
        $this->_exec('bin/mini_asset build --config app/config/assets.ini');
        $this->say("SASS rebuilt successfully!");
    }
}
