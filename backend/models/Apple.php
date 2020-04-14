<?php

namespace backend\models;

use Yii;

use common\models\User;

/**
 * This is the model class for table "apple".
 *
 * @property int $id
 * @property int $created_by
 * @property string $color
 * @property int $size
 * @property int $state
 * @property int $created_at
 * @property int $fall_at
 * @property int|null $deleted_at
 *
 * @property User $createdBy
 */
class Apple extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */


    //apple states
    const ON_TREE = 1;
    const FELL_TO_THE_GROUND = 2;
    const EATEN = 3;
    const ROTTEN = 4;

    //apple colors
    public $colors = array('green', 'lime', 'red', 'grey');


    public static function tableName()
    {
        return 'apple';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by', 'color', 'created_at'], 'required'],
            [['created_by', 'size', 'state', 'created_at', 'fall_at', 'deleted_at'], 'integer'],
            ['size', 'default', 'value' => 100],
            ['state', 'default', 'value' => 1],
            [['color'], 'string', 'max' => 10],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_by' => 'Created By',
            'color' => 'Color',
            'size' => 'Size',
            'state' => 'State',
            'created_at' => 'Created At',
            'fall_at' => 'Fall At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }


    public static function getApplesList()
    {
        $activeApples = self::find()
            ->select('id, color, size, state, FROM_UNIXTIME(created_at) as created_at, FROM_UNIXTIME(fall_at) as fall_at')
            ->where(['deleted_at' => NULL])
            ->asArray()
            ->all();
        
        return $activeApples;
    }



}
