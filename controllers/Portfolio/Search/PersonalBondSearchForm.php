<?php

namespace app\controllers\Portfolio\Search;

use app\models\Portfolio\PersonalBond;
use common\DataProvider\BaseArrayDataProvider;
use MongoDB\BSON\ObjectId;
use yii\base\Model;
use yii\data\ArrayDataProvider;

class PersonalBondSearchForm extends Model
{
    public string $issuerId = 'All';
    public string $bondId = 'All';

    public function rules(): array
    {
        return [
            [['issuerId', 'bondId'], 'string'],
        ];
    }

    public function search($params): ArrayDataProvider
    {
        $query = PersonalBond::find()
            ->with(['bond.issuerRating']);

        $this->load($params);
        if (!$this->validate()) {
            return new BaseArrayDataProvider([
                'allModels' => $query->all(),
            ]);
        }

        if ($this->bondId !== 'All' && $this->bondId !== '') {
            $query->andFilterWhere(['bond_id' => new ObjectId($this->bondId)]);
        }

        $arrayDataProvider = new BaseArrayDataProvider([
            'allModels' => $query->all(),
        ]);
        if ($this->issuerId !== 'All' && $this->issuerId !== '') {
            $arrayDataProvider->filter('bond.issuerRating._id', new ObjectId($this->issuerId));
        }

        return $arrayDataProvider;
    }
}
