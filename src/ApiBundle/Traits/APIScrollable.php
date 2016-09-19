<?php

namespace ApiBundle\Traits;

use ApiBundle\Exception\APIIncorrectRequestException;
use ApiBundle\Util\Scroll;
use Symfony\Component\HttpFoundation\Request;

/**
 * APIScrollable Trait.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
trait APIScrollable
{
    /**
     * Извлечение данных постраничного вывода: расчет параметров limit, offset, page.
     *
     * @param Request $request
     *
     * @return Scroll
     *
     * @throws APIIncorrectRequestException
     */
    protected function getScroll(Request $request)
    {
        $s = new Scroll();

        $s->page    = $request->get('page', 1);
        $s->limit   = $request->get('pagesize', 100);

        if (!is_numeric($s->page) || $s->page <= 0 || !is_numeric($s->limit) || $s->limit <= 0) {
            throw new APIIncorrectRequestException();
        }

        $s->offset = $s->limit * ($s->page - 1);

        return $s;
    }
}
