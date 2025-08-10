<?php

namespace src\Action\Share;

use src\Entity\Share\Deal\ShareDealRecord;
use src\Entity\Share\Share;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ShareDealSearchForm extends Model
{
    public function rules(): array
    {
        return [
        ];
    }

    public function search(Share $share, $params): ActiveDataProvider
    {
        $query = ShareDealRecord::find()
            ->joinWith(['share'])
            ->andWhere(['share_id' => $share->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}