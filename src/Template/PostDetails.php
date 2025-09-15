<?php

namespace silverorange\DevTest\Template;

use silverorange\DevTest\Context;

class PostDetails extends Layout
{
    protected function renderPage(Context $context): string
    {
        return <<<HTML
            <h1>{$context->title}</h1>
            <h2>Written by {$context->author} on {$context->created_at} ||
            Last modified on {$context->modified_at}</h2>
            <p>{$context->body}</p>
            HTML;
    }
}
