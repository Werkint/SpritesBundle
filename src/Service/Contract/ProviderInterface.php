<?php
namespace Werkint\Bundle\SpritesBundle\Service\Contract;

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
