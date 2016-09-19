<?php

namespace ApiBundle\Util;

/**
 * Объект с информацией для постраничного вывода.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class Scroll
{
    public $limit;
    public $page;
    public $offset;
}
