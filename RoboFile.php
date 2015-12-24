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
            ->monitor(__DIR__.'/assets/scss', function () {
                $this->buildSass();
            })
            ->monitor(__DIR__.'/assets/js', function () {
                $this->buildJs();
            })
            ->run()
        ;
    }

    public function copyFonts()
    {
        $this->taskCopyDir([
            __DIR__.'/assets/vendor/components-font-awesome/fonts' => __DIR__.'/web/fonts'
        ])
        ->run();
    }

    private function buildSass()
    {
        $this->say("Starting SASS rebuild");
        $this->_exec('bin/mini_asset build --config app/config/assets_css.ini');
        $this->say("SASS rebuilt successfully!");
    }

    private function buildJs()
    {
        $this->say("Starting JavaScript rebuild");
        $this->_exec('bin/mini_asset build --config app/config/assets_js.ini');
        $this->say("JavaScript rebuilt successfully!");
    }
}
