<?php

namespace app\controllers\Portfolio\Search;

use app\models\Portfolio\PersonalToken;
use common\DataProvider\BaseArrayDataProvider;
use MongoDB\BSON\ObjectId;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;

class PersonalTokenSearchForm extends Model
{
    public string $issuerId = 'All';
    public string $tokenId = 'All';

    public function rules(): array
    {
        return [
            [['issuerId', 'tokenId'], 'string'],
        ];
    }

    public function search($params): ArrayDataProvider
    {
        $query = PersonalToken::find()
            ->with(['token.issuerRating'])
            ->where(['user_id' => Yii::$app->user->identity->getId()]);

        $this->load($params);
        if (!$this->validate()) {
            return new BaseArrayDataProvider([
                'allModels' => $query->all(),
            ]);
        }

        if ($this->tokenId !== 'All' && $this->tokenId !== '') {
            $query->andFilterWhere(['token_id' => new ObjectId($this->tokenId)]);
        }

        $arrayDataProvider = new BaseArrayDataProvider([
            'allModels' => $query->all(),
        ]);
        if ($this->issuerId !== 'All' && $this->issuerId !== '') {
            $arrayDataProvider->filter('token.issuerRating._id', new ObjectId($this->issuerId));
        }

        return $arrayDataProvider;
    }
}
