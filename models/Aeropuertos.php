<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "aeropuertos".
 *
 * @property int $id
 * @property string|null $codigo
 * @property string $denominacion
 *
 * @property Vuelos[] $vuelos
 * @property Vuelos[] $vuelos0
 */
class Aeropuertos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'aeropuertos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['denominacion'], 'required'],
            [['codigo'], 'string', 'max' => 3],
            [['denominacion'], 'string', 'max' => 255],
            [['codigo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'denominacion' => 'Denominacion',
        ];
    }

    /**
     * Gets query for [[Vuelos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVuelos()
    {
        return $this->hasMany(Vuelos::className(), ['origen_id' => 'id']);
    }

    /**
     * Gets query for [[Vuelos0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVuelos0()
    {
        return $this->hasMany(Vuelos::className(), ['destino_id' => 'id']);
    }
}
