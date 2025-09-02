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
    public bool $onlyWithoutReports = false;

    public function rules(): array
    {
        return [
            [['name', '_pid'], 'string'],
            [['onlyWithoutReports'], 'boolean'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = Issuer::find()
            ->with([
                'shares',
                'activeShares',

                'businessReputationInfo',
                'esgRatingInfo',
                'creditRatingInfo',

                'additionalInfo',
                'employeeAmountRecords',
                'unreliableSupplier',

                'accountBalanceReports',
                'profitLossReports',
                'cashFlowReports',
            ])
            ->andWhere(['!=', Issuer::tableName() . '._pid', ''])
            ->addOrderBy([Issuer::tableName() . '._lastApiUpdateDate' => SORT_DESC]);
//            ->addOrderBy([
//                'CASE WHEN "_dateShareInfoModerated" IS NULL THEN 1 ELSE 0 END' => SORT_ASC,
//                Issuer::tableName() . '._dateShareInfoModerated' => SORT_DESC
//            ]);

        if ($this->onlyWithoutReports) {
            // Добавляем LEFT JOIN для таблиц отчетов и проверяем, что они пусты
            $query->leftJoin('accountBalanceReports', 'accountBalanceReports.issuer_id = ' . Issuer::tableName() . '.id')
                ->leftJoin('profitLossReports', 'profitLossReports.issuer_id = ' . Issuer::tableName() . '.id')
                ->leftJoin('cashFlowReports', 'cashFlowReports.issuer_id = ' . Issuer::tableName() . '.id')
                ->andWhere(['accountBalanceReports.id' => null])
                ->andWhere(['profitLossReports.id' => null])
                ->andWhere(['cashFlowReports.id' => null]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!Yii::$app->user->can(UserRole::admin->value)) {
            $query->andWhere([Issuer::tableName() . '."isVisible"' => true]);
        }

        if ($this->onlyWithoutReports) {
            $query->andWhere([Issuer::tableName() . '._pid' => $this->_pid]);
        }

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', '_pid', $this->_pid]);

        return $dataProvider;
    }
}