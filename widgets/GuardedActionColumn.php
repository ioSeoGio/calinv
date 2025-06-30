<?php

namespace app\widgets;

use lib\Access\AccessHelper;
use Yii;
use yii\grid\ActionColumn;

class GuardedActionColumn extends ActionColumn
{
    public array $showButtons = [];
    public array $permissions = [];
    public array $defaultButtons = [];

    public function __construct($config = [])
    {
        $this->defaultButtons = [
            'view' => ['icon' => 'eye-open', 'options' => []],
            'update' => ['icon' => 'pencil', 'options' => []],
            'delete' => ['icon' => 'trash', 'options' =>
                [
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                ]
            ],
        ];

        $this->showButtons = $config['showButtons'] ?? [];
        foreach ($this->defaultButtons as $buttonName => $settings) {
            if (!in_array($buttonName, $config['showButtons'])) {
                unset($this->defaultButtons[$buttonName]);
            }
        }

        parent::__construct($config);

        $this->checkButtons('visibleButtons');
        $this->checkButtons('buttons');
    }

    protected function initDefaultButtons(): void
    {
        foreach ($this->defaultButtons as $actionName => $options) {
            if (isset($this->permissions[$actionName])) {
                $permissions = $this->permissions[$actionName];
                if (is_scalar($permissions)){
                    $permissions = [$permissions];
                }
                if (AccessHelper::isGranted($permissions)) {
                    $this->initDefaultButton($actionName, $options['icon'], $options['options']);
                }
            } else {
                $this->initDefaultButton($actionName, $options['icon'], $options['options']);
            }
        }
    }

    protected function checkButtons($buttons): void
    {
        foreach ($this->$buttons as $buttonName => $function) {
            if (isset($this->permissions[$buttonName])) {
                $permissions = $this->permissions[$buttonName];
                if (is_scalar($permissions)){
                    $permissions = [$permissions];
                }
                if (!AccessHelper::isGranted($permissions)) {
                    $this->$buttons[$buttonName] = function () {
                        return false;
                    };
                }
            }
        }
    }
}