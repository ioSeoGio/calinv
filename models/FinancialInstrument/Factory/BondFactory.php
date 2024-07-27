<?php

namespace app\models\FinancialInstrument\Factory;

use app\controllers\FinancialInstrument\Form\BondForm;
use app\models\FinancialInstrument\Bond;
use MongoDB\BSON\ObjectId;

class BondFactory
{
    public function __construct(
    ) {
    }

    public function create(BondForm $form): Bond
    {
        $bond = new Bond();
        $bond->load([
            'name' => $form->name,
            'issuer_id' => new ObjectId($form->issuer_id),
            'denomination' => $form->denomination,
            'rate' => $form->rate,
            'currentPrice' => $form->currentPrice,
            'volumeIssued' => $form->volumeIssued,
            'maturityDate' => $form->maturityDate,
            'issueDate' => $form->issueDate,
        ], '');

        return $bond;
    }
}
