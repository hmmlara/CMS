<?php

class Pagination
{
    public static function paginator($page, $data, $limit)
    {
        $total = count($data);
        $totalPages = ceil($total / $limit);

        $page = max($page, 1);
        $page = min($page, $totalPages);

        $offset = ($page - 1) * $limit;

        if ($offset < 0) {
            $offset = 0;
        }

        return array_slice($data, $offset, $limit);
    }
}
