<?php

namespace App\Template;

class Paginator
{
    /**
     * @var array
     */
    public $list;

    /**
     * @var int
     */
    public $total;

    /**
     * @var int
     */
    public $offset;

    /**
     * @var int
     */
    public $limit;

    /**
     * @var callable
     */
    public $linkGenerator;

    /**
     * TemplatePaginator constructor.
     *
     * @param array $list
     * @param int $total
     * @param int $offset
     * @param int $limit
     * @param callable $linkGenerator
     */
    public function __construct(array $list, int $total, int $offset, int $limit, callable $linkGenerator)
    {
        $this->list = $list;
        $this->total = $total;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->linkGenerator = $linkGenerator;
    }

    /**
     * @return bool
     */
    public function needsPagination(): bool
    {
        return $this->total !== $this->limit;
    }

    /**
     * @return bool
     */
    public function hasPrevious(): bool
    {
        return $this->offset > 0;
    }

    /**
     * @return bool
     */
    public function hasNext(): bool
    {
        return ($this->offset + $this->limit) < $this->total;
    }

    /**
     * @return int
     */
    public function current(): int
    {
        return ($this->offset + $this->limit) / $this->limit;
    }

    /**
     * @return int
     */
    public function previous(): int
    {
        return $this->current() - 1;
    }

    /**
     * @return int
     */
    public function next(): int
    {
        return $this->current() + 1;
    }

    /**
     * @return int
     */
    public function pages(): int
    {
        return ceil($this->total / $this->limit);
    }

    /**
     * @param int $page
     *
     * @return string
     */
    public function link(int $page) {
        $callable = $this->linkGenerator;
        return $callable($page);
    }

    /**
     * @param int $elements
     *
     * @return array
     */
    public function links($elements = 3): array
    {
        $offset = $this->current();
        $offset = $offset - 2;
        if ($offset < 0) {
            $offset = 0;
        }

        $links = range(1, $this->pages());
        $links = array_slice($links, $offset, $elements);
        return $links;
    }

    /**
     * @param int $offsetStart
     * @param int $offsetEnd
     * @param int $lastCount
     *
     * @return bool
     */
    public static function exactCheck(int $offsetStart, int $offsetEnd, int $lastCount): bool
    {
        return $offsetStart <= ($lastCount + 1) && $offsetEnd > ($lastCount + 1);
    }

    /**
     * @param int $offsetStart
     * @param int $offsetEnd
     * @param int $lastCount
     * @param int $objectCount
     *
     * @return bool
     */
    public static function groupCheck(int $offsetStart, int $offsetEnd, int $lastCount, int $objectCount): bool
    {
        $countAfter = $lastCount + $objectCount;
        return ($offsetStart < $countAfter) || ($offsetEnd > $lastCount);
    }
}