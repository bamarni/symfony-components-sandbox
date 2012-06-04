<?php

namespace Sandbox\Templating;

use Symfony\Component\Templating\TemplateNameParser as BaseTemplateNameParser;

class TemplateNameParser extends BaseTemplateNameParser
{
    public function parse($name)
    {
        $name = str_replace(':', '/', $name);

        return parent::parse($name);
    }
}
