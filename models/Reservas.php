<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reservas".
 *
 * @property int $id
 * @property int $usuario_id
 * @property int $vuelo_id
 * @property float $asiento
 * @property string $created_at
 *
 * @property Usuarios $usuario
 * @property Vuelos $vuelo
 */
class Reservas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reservas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'vuelo_id', 'asiento'], 'required'],
            [['usuario_id', 'vuelo_id'], 'default', 'value' => null],
            [['usuario_id', 'vuelo_id'], 'integer'],
            [['asiento'], 'number'],
            [['created_at'], 'safe'],
            ['vuelo.codigo', 'unique'],
            [['asiento'], function ($attribute, $params, $validator) {
                $plazas = $this->vuelo->plazas;
                if ($this->asiento < 1 || $this->asiento > $plazas) {
                    $this->addError($attribute, "El número de asiento debe estar comprendido entre 1 y $plazas");
                }
            }],
            [['vuelo_id', 'asiento'], 'unique', 'targetAttribute' => ['vuelo_id', 'asiento']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            [['vuelo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vuelos::className(), 'targetAttribute' => ['vuelo_id' => 'id']],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['vuelo.codigo']);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario_id' => 'Usuario ID',
            'vuelo_id' => 'Vuelo ID',
            'asiento' => 'Asiento',
            'created_at' => 'Fecha Hora',
        ];
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id']);
    }

    /**
     * Gets query for [[Vuelo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVuelo()
    {
        return $this->hasOne(Vuelos::className(), ['id' => 'vuelo_id']);
    }
}
