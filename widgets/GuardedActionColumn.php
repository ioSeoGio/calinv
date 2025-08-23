<?php

namespace app\widgets;

use lib\Access\AccessHelper;
use Yii;
use yii\grid\ActionColumn;
use yii\helpers\Html;

class GuardedActionColumn extends ActionColumn
{
    private const array DEFAULT_BUTTONS = [
        'view' => [
            'icon' => 'bi bi-eye',
            'options' => [
                'class' => 'btn btn-sm btn-outline-primary',
            ],
            'url' => null,
        ],
        'update' => [
            'icon' => 'bi bi-pencil',
            'options' => [
                'class' => 'btn btn-sm btn-outline-primary',
            ],
            'url' => null,
        ],
        'delete' => [
            'icon' => 'bi bi-trash',
            'options' => [
                'class' => 'btn btn-sm btn-outline-danger',
                'data-method' => 'post',
            ],
            'url' => null,
        ],
    ];

    public array $permissions = [];
    public array $buttonsConfig = [];
    private array $showButtons = [];

    protected function initDefaultButtons(): void
    {
        foreach ($this->buttonsConfig as $buttonName => $buttonSettings) {
            $buttonKey = is_string($buttonSettings) ? $buttonSettings : $buttonName;
            $buttonSettings = is_string($buttonSettings) ? [] : $buttonSettings;

            if (isset(self::DEFAULT_BUTTONS[$buttonKey])) {
                // Update existing button configuration
                $this->showButtons[$buttonKey] = array_merge(
                    self::DEFAULT_BUTTONS[$buttonKey],
                    $buttonSettings
                );
            } else {
                $r = array_merge([
                    'icon' => '',
                    'url' => null,
                ], $buttonSettings);
                $r['options'] = array_merge([
                    'class' => 'btn btn-sm btn-outline-secondary',
                ], $buttonSettings['options'] ?? []);

                $this->showButtons[$buttonKey] = $r;
            }
        }

        // Set the template based on visible buttons
        $this->template = implode(' ', array_map(
            fn($button) => '{' . $button . '}',
            array_keys($this->showButtons)
        ));

        foreach ($this->showButtons as $actionName => $settings) {
            if ($this->checkPermission($actionName)) {
                $this->initButton($actionName, $settings);
            }
        }
    }

    protected function initButton(string $actionName, array $settings): void
    {
        $this->buttons[$actionName] = function ($url, $model, $key) use ($actionName, $settings) {
            $options = array_merge([
                'title' => Yii::t('yii', ucfirst($actionName)),
                'aria-label' => Yii::t('yii', ucfirst($actionName)),
                'data-pjax' => '0',
            ], $settings['options']);

            // Use custom URL if provided
            if ($settings['url'] !== null) {
                $url = is_callable($settings['url']) 
                    ? call_user_func($settings['url'], $model, $key)
                    : $settings['url'];
            }
            if (isset($settings['isVisible']) && is_callable($settings['isVisible'])) {
                $isVisible = call_user_func($settings['isVisible'], $model, $key);
                if ($isVisible === false) {
                    return '';
                }
            }

            $icon = Html::tag('i', '', ['class' => $settings['icon']]);
            return Html::a($icon, $url, $options);
        };
    }

    protected function checkPermission(string $buttonName): bool
    {
        if (!isset($this->permissions[$buttonName])) {
            return true;
        }

        $permissions = (array) $this->permissions[$buttonName];
        return AccessHelper::isGranted($permissions);
    }
}