<?php

namespace lib;

use lib\Exception\UserException\ApiBadRequestException;
use lib\Exception\UserException\ApiBadResponseException;
use lib\Exception\UserException\ApiInternalErrorException;
use lib\Exception\UserException\ApiLightTemporaryUnavailableException;
use lib\Exception\UserException\ApiNotFoundException;
use Yii;
use yii\base\Response;
use yii\base\UserException;
use yii\web\ErrorHandler as YiiErrorHandler;

class ErrorHandler extends YiiErrorHandler
{
    protected function renderException($exception): void
    {
        Yii::error("[Exception] {$exception->getMessage()}" . json_encode($exception), 'exception');

        if (YII_ENV_PROD || EnvGetter::getBool('HIDE_SHIT_EXCEPTIONS', false)) {
            $this->handlerProd($exception)->send();
            return;
        }

        parent::renderException($exception);
    }

    private function handlerProd(\Throwable $exception): Response
    {
        if ($exception instanceof ApiBadResponseException) {
            $this->printError($exception, FlashType::warning, 'Стороннее api ответило невалидным форматом данных.');
            return $this->redirect();
        }

        if ($exception instanceof ApiNotFoundException) {
            $this->printError($exception, FlashType::warning, 'Стороннее api не нашло запрашиваемые данные.');
            return $this->redirect();
        }

        if ($exception instanceof ApiLightTemporaryUnavailableException) {
            $this->printError($exception, FlashType::error, 'Стороннее api временно недоступно.');
            return $this->redirect();
        }

        if ($exception instanceof ApiInternalErrorException || $exception instanceof ApiBadRequestException) {
            $this->printError($exception, FlashType::error, 'Стороннее api ответило ошибкой.');
            return $this->redirect();
        }

        if ($exception instanceof UserException) {
            $this->printError($exception, FlashType::error, "Произошла ошибка, попробуйте позже.");
            return $this->redirect();
        }

        $this->printError($exception, FlashType::error, 'Произошла внутренняя ошибка.');
        return $this->redirect();
    }

    private function printError(\Throwable $e, FlashType $flashType, string $defaultMessage): void
    {
        Yii::$app->session->setFlash('error', 'govno');
        Yii::$app->session->setFlash($flashType->value, $e->getMessage() ?: $defaultMessage);
    }

    private function redirect(): Response
    {
        if (Yii::$app->request->referrer !== Yii::$app->homeUrl) {
            return Yii::$app->getResponse()->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }

        return Yii::$app->getResponse();
    }
}