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
            [['created_by', 'color', 'size', 'state', 'created_at', 'fall_at'], 'required'],
            [['created_by', 'size', 'state', 'created_at', 'fall_at', 'deleted_at'], 'integer'],
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
}
