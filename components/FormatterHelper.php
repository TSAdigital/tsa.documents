<?php

namespace app\components;

use yii\helpers\Html;
use yii\i18n\Formatter;

class FormatterHelper extends Formatter {
    public static function asPassportSerial($value) {
        return preg_replace('#^(\d{2})(\d{2})$#', '$1 $2', $value);
    }
}