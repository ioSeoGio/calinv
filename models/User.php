<?php

namespace app\models;

use Yii;
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
			'fio',
			'email',
			'phone_number',
			'password_hash',
			'auth_key',
			'access_token',
			'created_at',
		];
	}

	public function rules()
	{
		return [
			[['username', 'fio', 'email', 'phone_number', 'password_hash', 'auth_key', 'access_token', 'created_at'], 'safe'],
			[['username', 'fio', 'email', 'phone_number'], 'required'],
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
}
