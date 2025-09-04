<?php

namespace lib\MetaTag;

use src\Entity\Issuer\Issuer;
use Yii;
use yii\web\View;

class MetaTagManager
{
    public static function registerIssuerTags(View $view, Issuer $issuer): void
    {
        MetaTagManager::registerDescriptionTag($view, "УНП $issuer->_pid");
        MetaTagManager::registerKeywordsTag($view, [
            $issuer->name,
            $issuer->_pid,
            $issuer->addressInfo?->fullAddress,
            $issuer->addressInfo?->site,
            $issuer->typeOfActivityCode,
            $issuer->typeOfActivity,
            $issuer->addressInfo?->phones,
        ]);
    }

    public static function registerDescriptionTag(View $view, string $additional = ''): void
    {
        self::registerMetaTag($view, 'description', $additional . ' ' . self::getMetaDescription());
    }

    public static function registerKeywordsTag(View $view, array $additional = []): void
    {
        $additional = array_filter($additional, fn ($value) => !empty($value));
        self::registerMetaTag($view, 'keywords', implode(', ', $additional) . self::getMetaKeywords());
    }

    public static function registerMetaTag(View $view, string $metaTag, string $values): void
    {
        $view->registerMetaTag(['name' => $metaTag, 'content' => $values]);
    }

    public static function getMetaDescription(): string
    {
        return Yii::$app->params['meta_description'] ?? '';
    }

    public static function getMetaKeywords(): string
    {
        return Yii::$app->params['meta_keywords'] ?? '';
    }
}
