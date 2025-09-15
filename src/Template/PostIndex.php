<?php

namespace silverorange\DevTest\Template;

use silverorange\DevTest\Context;

class PostIndex extends Layout
{
    protected function renderPage(Context $context): string
    {
        return <<<HTML
            <p>TOTAL POSTS: {$context->content}</p>

            {$context->body}
            HTML;
    }
}
