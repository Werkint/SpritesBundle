<?php
namespace Werkint\Bundle\SpritesBundle\Service\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * TileProcessEvent.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class TileProcessEvent extends Event
{
    protected $image;
    protected $spriteName;
    protected $tileName;

    /**
     * @param \Imagick $image
     * @param string $spriteName
     * @param string $tileName
     */
    public function __construct(
        \Imagick $image,
        $spriteName,
        $tileName
    ) {
        $this->image = $image;
        $this->spriteName = $spriteName;
        $this->tileName = $tileName;
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
    public function getTileName()
    {
        return $this->tileName;
    }

}
