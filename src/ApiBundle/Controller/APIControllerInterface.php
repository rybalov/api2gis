<?php

namespace ApiBundle\Controller;

/**
 * API Controller Interface.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
interface APIControllerInterface
{
    const SEARCH_BUILD_TYPE_STREET  = 'street';
    const SEARCH_BUILD_TYPE_ALL     = 'all';
    const SEARCH_COMP_TYPE_ID       = 'id';
    const SEARCH_COMP_TYPE_ADDRESS  = 'address';
    const SEARCH_COMP_TYPE_NAME     = 'name';
    const SEARCH_COMP_TYPE_CATEGORY = 'category';
    const SEARCH_COMP_TYPE_RADIUS   = 'radius';
    const SEARCH_COMP_TYPE_BOUND    = 'bound';
    const SEARCH_COMP_TYPE_ALL      = 'all';
    const SEARCH_CATEGORY_TYPE_ALL  = 'all';
    const SEARCH_CATEGORY_TYPE_NAME = 'name';
}
