<?php

namespace src\ViewHelper;

use lib\FrontendHelper\DetailViewCopyHelper;

class IssuerPhonesViewHelper
{
    public static function render(?string $phones): string
    {
        if ($phones === null) {
            return '';
        }

        $phones = explode(',', $phones);

        if (is_array($phones)) {
            $result = '';
            foreach ($phones as $phone) {
                $result .= DetailViewCopyHelper::renderValue($phone);
            }
        } else {
            $result = DetailViewCopyHelper::renderValue($phones);
        }

        return $result;
    }
}