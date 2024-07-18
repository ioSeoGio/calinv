<?php

namespace common\Database;

#[\Attribute]
class EmbeddedOne
{
    public function __construct(
        public string $propertyName,
        public string $objectClass,
    ) {
    }
}
