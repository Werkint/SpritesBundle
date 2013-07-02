<?php
namespace Werkint\Bundle\SpritesBundle\Service;

/**
 * ProviderInterface.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface ProviderInterface
{
    /**
     * @return array
     */
    public function getImages();
}
