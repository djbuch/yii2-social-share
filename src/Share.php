<?php

namespace bigpaulie\social\share;

use bigpaulie\social\share\Widget;
use yii\helpers\Html;

class Share extends Widget {

    public $list;

    public function run() {
        echo Html::beginTag($this->tag , $this->htmlOptions);
        if ($this->list === null)
            $this->list = array_keys($this->networks);
        foreach ($this->list as $val) {
            echo $this->parseTemplate($val);
        }
        echo Html::endTag($this->tag);
    }

}
