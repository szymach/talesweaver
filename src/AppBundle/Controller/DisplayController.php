<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Scene;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class DisplayController
{
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function sceneAction(Scene $scene)
    {
        return $this->templating->renderResponse(
            'scene/display.html.twig',
            ['scene' => $scene]
        );
    }
}
