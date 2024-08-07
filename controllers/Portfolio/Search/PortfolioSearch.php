<?php

namespace app\controllers\Portfolio\Search;

use app\models\Portfolio\PersonalToken;
use app\models\User;
use common\DataProvider\BaseArrayDataProvider;
use MongoDB\BSON\ObjectId;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;

class PortfolioSearch extends Model
{
    public string $username = '';

    public function rules(): array
    {
        return [
            [['username'], 'string'],
        ];
    }

    public function search($params): ArrayDataProvider
    {
        $query = User::find()
            ->with([
                'personalBonds',
                'personalShares',
                'personalTokens',
            ])
            ->andFilterWhere(['username' => $this->username]);

        $arrayDataProvider = new BaseArrayDataProvider([
            'allModels' => $query->all(),
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $arrayDataProvider;
        }

        return $arrayDataProvider;
    }
}
