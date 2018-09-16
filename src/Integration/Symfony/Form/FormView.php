<?php

declare(strict_types=1);

namespace Talesweaver\Integration\Symfony\Form;

use Symfony\Component\Form\FormView as SymfonyFormView;
use Talesweaver\Application\Form\FormViewInterface;

class FormView extends SymfonyFormView implements FormViewInterface
{
    /**
     * @var array
     */
    public $vars;

    /**
     * @var SymfonyFormView
     */
    public $parent;

    /**
     * @var array
     */
    public $children = [];

    public function __construct(?SymfonyFormView $formFiew)
    {
        parent::__construct();
        $this->vars = $formFiew->vars;
        $this->parent = $formFiew->parent;
        $this->children = $formFiew->children;
    }
}
