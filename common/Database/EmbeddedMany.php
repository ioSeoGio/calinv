<?php

namespace common\Database;

#[\Attribute(\Attribute::IS_REPEATABLE, \Attribute::TARGET_CLASS)]
class EmbeddedMany
{
    public function __construct(
        public string $propertyName,
        public string $objectClass,
    ) {
    }
}
