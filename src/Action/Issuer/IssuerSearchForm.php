<?php

namespace src\Action\Issuer;

use src\Entity\Issuer\Issuer;
use src\Entity\User\UserRole;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class IssuerSearchForm extends Model
{
    public string $name = '';
    public string $_pid = '';

    public function rules(): array
    {
        return [
            [['name', '_pid'], 'string'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = Issuer::find()
            ->with(['shares', 'businessReputationInfo'])
            ->andWhere(['!=', Issuer::tableName() . '._pid', ''])
            ->addOrderBy([Issuer::tableName() . '.name' => SORT_ASC]);

        if (!Yii::$app->user->can(UserRole::admin->value)) {
            $query->andWhere(['not', ['_dateShareInfoModerated' => null]]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', '_pid', $this->_pid]);

        return $dataProvider;
    }
}