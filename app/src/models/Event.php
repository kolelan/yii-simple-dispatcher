<?php

namespace app\models;

use app\events\EventInterface;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "events".
 *
 * @property int $id
 * @property string $name
 * @property string $group_name
 * @property array|mixed $payload
 * @property bool $success
 * @property int $counter
 * @property array|mixed $data
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Event extends ActiveRecord implements EventInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%events}}';
    }

    /**
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'group_name'], 'required'],
            [['payload', 'data'], 'safe'],
            [['success','pending'], 'boolean'],
            [['counter'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'group_name' => 'Group Name',
            'payload' => 'Payload',
            'success' => 'Success',
            'pending' => 'Pending',
            'counter' => 'Counter',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}