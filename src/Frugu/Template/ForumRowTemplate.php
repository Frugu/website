<?php

declare(strict_types=1);

namespace Frugu\Template;

class ForumRowTemplate
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var null|string
     */
    public $subtype;

    /**
     * @var null|string
     */
    public $preview = null;

    /**
     * @var null|string
     */
    public $link = null;

    /**
     * @var null|array
     */
    public $additionalLinks = null;

    /**
     * @var null|array
     */
    public $details = null;
}
