<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\IdentityInterface;

/**
 * Este modelo es para recuperar usuarios.
 *
 * @property string $correo
 */
class Recuperar extends Model
{

    public $correo;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['correo'], 'required'],
            [['correo'], 'string', 'max' => 255],
            [['correo'], 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'correo' => 'Correo',
        ];
    }
}
