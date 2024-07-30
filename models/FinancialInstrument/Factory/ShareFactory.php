<?php

namespace app\models\FinancialInstrument\Factory;

use app\controllers\FinancialInstrument\Form\BondForm;
use app\controllers\FinancialInstrument\Form\ShareForm;
use app\models\FinancialInstrument\Bond;
use app\models\FinancialInstrument\Share;
use MongoDB\BSON\ObjectId;

class ShareFactory
{
    public function __construct(
    ) {
    }

    public function create(ShareForm $form): Share
    {
        $share = new Share();
        $share->load([
            'name' => $form->name,
            'issuer_id' => new ObjectId($form->issuer_id),
            'denomination' => $form->denomination,
            'volumeIssued' => $form->volumeIssued,
            'currentPrice' => $form->currentPrice,
        ], '');

        return $share;
    }
}
