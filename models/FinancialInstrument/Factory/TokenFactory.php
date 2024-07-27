<?php

namespace app\models\FinancialInstrument\Factory;

use app\controllers\FinancialInstrument\Form\BondForm;
use app\controllers\FinancialInstrument\Form\ShareForm;
use app\controllers\FinancialInstrument\Form\TokenForm;
use app\models\FinancialInstrument\Bond;
use app\models\FinancialInstrument\Share;
use app\models\FinancialInstrument\Token;
use MongoDB\BSON\ObjectId;

class TokenFactory
{
    public function __construct(
    ) {
    }

    public function create(TokenForm $form): Token
    {
        $share = new Token();
        $share->load([
            'name' => $form->name,
            'issuer_id' => new ObjectId($form->issuer_id),
            'denomination' => $form->denomination,
            'volumeIssued' => $form->volumeIssued,
            'rate' => $form->rate,
            'currentPrice' => $form->currentPrice,
            'maturityDate' => $form->maturityDate,
            'issueDate' => $form->issueDate,
        ], '');

        return $share;
    }
}
