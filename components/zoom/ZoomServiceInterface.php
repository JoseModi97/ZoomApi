<?php

namespace app\components\zoom;

interface ZoomServiceInterface
{
    /**
     * Constructor to inject the ZoomComponent.
     * @param ZoomComponent $zoom The ZoomComponent instance.
     * @param array $config Name-value pairs that will be used to initialize the object properties.
     */
    public function __construct(ZoomComponent $zoom, $config = []);
}
