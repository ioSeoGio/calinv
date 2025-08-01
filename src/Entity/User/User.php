<?php

namespace src\Entity\User;

use lib\Database\BaseActiveRecord;
use src\Entity\PersonalShare\PersonalShare;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Сущность юзера
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property string $access_token
 * @property int $created_at
 *
 * @property array<PersonalShare> $personalShares
 */

class User extends BaseActiveRecord implements IdentityInterface
{
    public const string ROLE_ADMIN = 'admin';
    public const string ROLE_USER = 'user';

	public static function tableName(): string
    {
		return 'user';
	}

	public function rules(): array
    {
		return [
			[['username', 'email', 'password_hash', 'auth_key', 'access_token', 'created_at'], 'safe'],
			[['username', 'email'], 'required'],
			[['email'], 'email'],
			[['username', 'email'], 'unique'],
		];
	}

    public function getPersonalShares(): ActiveQuery
    {
        return $this->hasMany(PersonalShare::class, ['user_id' => 'id']);
    }


    public function getShareInfo(): array
    {
        $shareAmount = 0;
        $shareCost = 0;
        foreach ($this->getPersonalShares()->with('share')->each() as $personalShare) {
            $shareAmount += $personalShare->amount;
            $shareCost += $personalShare->amount * $personalShare->share->currentPrice;
        }

        return [
            'amount' => $shareAmount,
            'moneyCost' => $shareCost,
        ];
    }

	public static function findIdentity($id): User|IdentityInterface|null
    {
		return static::findOne($id);
	}

	public static function findIdentityByAccessToken($token, $type = null): User|IdentityInterface|null
    {
		return static::findOne(['access_token' => $token]);
	}

	public static function findByEmail($email): ?User
    {
		return static::findOne(['email' => $email]);
	}

	public function getId(): string
    {
		return (string) $this->id;
	}

	public function getAuthKey(): ?string
    {
		return $this->auth_key;
	}

	public function validateAuthKey($authKey): bool
    {
		return $this->auth_key === $authKey;
	}

	public function validatePassword($password): bool
    {
		return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
	}

	public function setPassword($password): void
    {
		$this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
	}

	public function generateAuthKey(): void
    {
		$this->auth_key = Yii::$app->getSecurity()->generateRandomString();
	}

	public function generateAccessToken(): void
    {
		$this->access_token = Yii::$app->getSecurity()->generateRandomString();
	}
}
