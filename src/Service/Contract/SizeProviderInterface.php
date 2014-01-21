<?php
namespace Werkint\Bundle\SpritesBundle\Service\Contract;

/**
 * SizeProviderInterface.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface SizeProviderInterface
{
    /**
     * @return array
     */
    public function getSizes();
}
