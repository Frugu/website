<?php

declare(strict_types=1);

namespace App\Entity\Forum;

abstract class ConversationType
{
    const NORMAL = 'normal';
    const STICKY = 'sticky';
    const ANNOUNCEMENT = 'announcement';
    const GLOBAL = 'global';
    const REPLY = 'reply';
}
