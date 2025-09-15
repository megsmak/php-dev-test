<?php

namespace silverorange\DevTest\Template;

use silverorange\DevTest\Context;

class PostImporter extends Layout
{
    protected function renderPage(Context $context): string
    {

        return <<<HTML
            <p>Please paste your JSON text to upload a post to the database. Please note the following formatting:</p>
            <blockquote>
                <p>&lbrace;</p>
                <p>"id": "",</p>
                <p>"title": "",</p>
                <p>"body": "",</p>
                <p>"created_at": "",</p>
                <p>"modified_at": "",</p>
                <p>"author":""</p>
                <p>&rbrace;</p></blockquote>

                <p>{$context->error}</p>

            <form method="post" accept-charset="utf-8" action="import" class="form" novalidate="novalidate">
                <div class="form-field form-field--required">
                    <label class="form-field__label" for="import_file">JSON file contents<span class="form-field__label-required"> (required)</span></label>
                    <div class="form-field__contents">
                        <textarea name="import_file" id="import_file" class="entry" value="" size="17" /></textarea>
                    </div>
                </div>
                <div class="form-field form-field--required">
                                <label class="form-field__label" for="password">Password: <span class="form-field__label-required"> (required)</span></label>
                                <div class="form-field__contents">
                                    <input type="password" name="password" id="password" class="entry" maxlength="255" />
                                </div>
                            </div>
                <div class="form-field form-field--required">
                    <input type="submit" value="Submit">
                </div>
            </form>
            HTML;
    }
}
