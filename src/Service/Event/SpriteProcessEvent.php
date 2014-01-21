<?php
namespace Werkint\Bundle\SpritesBundle\Service\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * SpriteProcessEvent.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SpriteProcessEvent extends Event
{
    protected $image;
    protected $spriteName;
    protected $scss;

    public function __construct(
        \Imagick $image,
        $spriteName,
        &$scss
    ) {
        $this->image = $image;
        $this->spriteName = $spriteName;
        $this->scss = $scss;
    }

    // -- Getters ---------------------------------------

    /**
     * @return \Imagick
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getSpriteName()
    {
        return $this->spriteName;
    }

    /**
     * @return string
     */
    public function &getScss()
    {
        return $this->scss;
    }

}
