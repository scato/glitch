<?php

namespace Glitch\Runtime;

interface ActionInterface
{
    public function fire(ValueInterface $value);
}

