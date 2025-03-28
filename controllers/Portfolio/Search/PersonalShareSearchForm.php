<?php

namespace app\controllers\Portfolio\Search;

use app\models\Portfolio\PersonalShare;
use common\DataProvider\BaseArrayDataProvider;
use MongoDB\BSON\ObjectId;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

class PersonalShareSearchForm extends Model
{
    public string $issuerId = 'All';
    public string $shareId = 'All';

    public function rules(): array
    {
        return [
            [['issuerId', 'shareId'], 'string'],
        ];
    }

    public function search($params): ArrayDataProvider
    {
        $query = PersonalShare::find()
            ->with(['share.issuerRating'])
            ->where(['user_id' => new ObjectId($params['userId'] ?? Yii::$app->user->identity->getId())]);

        $this->load($params);
        if (!$this->validate()) {
            return new BaseArrayDataProvider([
                'allModels' => $query->all(),
            ]);
        }

        if ($this->shareId !== 'All' && $this->shareId !== '') {
            $query->andFilterWhere(['share_id' => new ObjectId($this->shareId)]);
        }

        $arrayDataProvider = new BaseArrayDataProvider([
            'allModels' => $query->all(),
        ]);
        if ($this->issuerId !== 'All' && $this->issuerId !== '') {
            $arrayDataProvider->filter('share.issuerRating._id', new ObjectId($this->issuerId));
        }

        return $arrayDataProvider;
    }
}
