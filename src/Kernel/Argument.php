<?php

declare(strict_types=1);

namespace Hi\Kernel;

use Hi\Kernel\Argument\Parser;

class Argument
{
    protected Parser $parser;

    public function __construct(array $argv)
    {
        $this->parser = new Parser();
        $this->parser->parse($argv);
    }

    public function getCommand()
    {
        return $this->parser->get(0);
    }

    public function getAction()
    {
        return $this->parser->get(1);
    }

    public function getOption(string $key)
    {
        return $this->parser->get($key);
    }
}
