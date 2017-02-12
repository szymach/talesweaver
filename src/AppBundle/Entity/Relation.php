<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

class Relation
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Character
     */
    private $owner;

    /**
     * @var Character
     */
    private $relation;

    public function getId()
    {
        return $this->id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner(Character $owner)
    {
        $this->owner = $owner;
    }

    public function getRelation()
    {
        return $this->relation;
    }

    public function setRelation(Character $relation)
    {
        $this->relation = $relation;
    }
}
