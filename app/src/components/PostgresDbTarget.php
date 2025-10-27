<?php

namespace app\components;

use yii\log\DbTarget;

class PostgresDbTarget extends DbTarget
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // Для PostgreSQL используем CURRENT_TIMESTAMP
        $this->logTable = '{{%log}}';
    }

    /**
     * @inheritdoc
     */
    public function getMessagePrefix($message)
    {
        if ($this->prefix !== null) {
            return call_user_func($this->prefix, $message);
        }

        if (Yii::$app === null) {
            return '';
        }

        $request = Yii::$app->getRequest();
        $ip = $request instanceof Request ? $request->getUserIP() : '-';

        /* @var $user \yii\web\User */
        $user = Yii::$app->has('user', true) ? Yii::$app->get('user') : null;
        if ($user && ($identity = $user->getIdentity(false))) {
            $userID = $identity->getId();
        } else {
            $userID = '-';
        }

        /* @var $session \yii\web\Session */
        $session = Yii::$app->has('session', true) ? Yii::$app->get('session') : null;
        $sessionID = $session && $session->getIsActive() ? $session->getId() : '-';

        return "[$ip][$userID][$sessionID]";
    }

    /**
     * @inheritdoc
     */
    public function export()
    {
        if ($this->db->getTransaction()) {
            // Создаем новое соединение чтобы избежать deadlock
            $db = \Yii::$createObject($this->db);
            $this->db = $db;
        }

        parent::export();
    }
}