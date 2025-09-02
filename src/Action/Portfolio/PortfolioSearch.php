<?php

namespace src\Action\Portfolio;

use src\Entity\User\User;
use src\Entity\User\UserRole;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PortfolioSearch extends Model
{
    public string $username = '';

    public function rules(): array
    {
        return [
            [['username'], 'string'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = User::find()
            ->with(['personalShares'])
            ->orderBy(['username' => SORT_DESC]);

        if (!Yii::$app->user->can(UserRole::admin->value)) {
            $query->andWhere(['isPortfolioVisible' => true]);
            $query->andWhere(['isPortfolioPublic' => true]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['ilike', 'username', $this->username]);

        return $dataProvider;
    }
}