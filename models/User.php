<?php

namespace app\models;

use app\models\Portfolio\PersonalBond;
use app\models\Portfolio\PersonalShare;
use app\models\Portfolio\PersonalToken;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
	public static function collectionName(): string
    {
		return 'user';
	}

	public function attributes(): array
    {
		return [
			'_id',
			'username',
			'email',
			'password_hash',
			'auth_key',
			'access_token',
			'created_at',
		];
	}

	public function rules()
	{
		return [
			[['username', 'email', 'password_hash', 'auth_key', 'access_token', 'created_at'], 'safe'],
			[['username', 'email'], 'required'],
			[['email'], 'email'],
			[['username', 'email'], 'unique'],
		];
	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if ($this->isNewRecord) {
				$this->created_at = new \MongoDB\BSON\UTCDateTime();
			}
			return true;
		}
		return false;
	}

	public static function findIdentity($id)
	{
		return static::findOne($id);
	}

	public static function findIdentityByAccessToken($token, $type = null)
	{
		return static::findOne(['access_token' => $token]);
	}

	public static function findByEmail($email)
	{
		return static::findOne(['email' => $email]);
	}

	public function getId()
	{
		return (string) $this->_id;
	}

	public function getAuthKey()
	{
		return $this->auth_key;
	}

	public function validateAuthKey($authKey)
	{
		return $this->auth_key === $authKey;
	}

	public function validatePassword($password)
	{
		return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
	}

	public function setPassword($password)
	{
		$this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
	}

	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->getSecurity()->generateRandomString();
	}

	public function generateAccessToken()
	{
		$this->access_token = Yii::$app->getSecurity()->generateRandomString();
	}

    public function getPersonalBonds(): ActiveQuery|ActiveQueryInterface
    {
        return $this->hasMany(PersonalBond::class, ['user_id' => '_id']);
    }

    public function getPersonalShares(): ActiveQuery|ActiveQueryInterface
    {
        return $this->hasMany(PersonalShare::class, ['user_id' => '_id']);
    }

    public function getPersonalTokens(): ActiveQuery|ActiveQueryInterface
    {
        return $this->hasMany(PersonalToken::class, ['user_id' => '_id']);
    }

    public function getSharesInfo(): array
    {
        $shareAmount = 0;
        $shareMoneyCost = 0;
        foreach ($this->getPersonalShares()->all() as $personalShare) {
            $shareAmount += $personalShare->amount;
            $shareMoneyCost += $personalShare->amount * $personalShare->share->currentPrice;
        }

        $bondAmount = 0;
        $bondMoneyCost = 0;
        foreach ($this->getPersonalBonds()->all() as $personalBond) {
            $bondAmount += $personalBond->amount;
            $bondMoneyCost += $personalBond->amount * $personalBond->bond->currentPrice;
        }

        $tokenAmount = 0;
        $tokenMoneyCost = 0;
        foreach ($this->getPersonalTokens()->all() as $personalToken) {
            $tokenAmount += $personalToken->amount;
            $tokenMoneyCost += $personalToken->amount * $personalToken->token->currentPrice;
        }

        $allCost = $shareMoneyCost + $bondMoneyCost + $tokenMoneyCost;
        return [
            'shareAmount' => $shareAmount,
            'shareMoneyCost' => $shareMoneyCost,
            'sharePercentage' => $shareMoneyCost / $allCost * 100,

            'bondAmount' => $bondAmount,
            'bondMoneyCost' => $bondMoneyCost,
            'bondPercentage' => $bondMoneyCost / $allCost * 100,

            'tokenAmount' => $tokenAmount,
            'tokenMoneyCost' => $tokenMoneyCost,
            'tokenPercentage' => $tokenMoneyCost / $allCost * 100,
        ];
    }
}
