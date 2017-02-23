<?php

namespace bigpaulie\social\share;

use Yii;
use yii\helpers\Url;
use \yii\web\View;
use bigpaulie\social\share\ShareAsset;

class Widget extends \yii\base\Widget {

    public $url;
    public $tag = 'ul';
    public $text = 'Share on {network}';
    public $type = 'small';
    public $via = '';
    public $template = '<li>{button}</li>';
    public $htmlOptions = [];
    protected $networks = [
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u={url}',
        'google-plus' => [
            'url' => 'https://plus.google.com/share?url={url}',
            'btn_class' => 'google'
        ],
        'twitter' => 'https://twitter.com/share?url={url}&text={title}&via={via}',
        'linkedin' => 'https://www.linkedin.com/shareArticle?mini=true&url={url}',
        'vk' =>  'http://vkontakte.ru/share.php?url={url}',
        'odnoklassniki' => 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl={url}',
        'email' => [
            'url' => 'mailto:?subject={title}&body={url}',
            'icon_class' => 'envelope',
            'btn_class' => 'primary'
        ]
    ];
    public $missButton = array();

    public function init() {

        if (!$this->id) {
            $this->id = $this->getId();
        }
        
        if(!isset($this->htmlOptions['id'])){
            $this->htmlOptions['id'] = $this->id;
        }

        if (!$this->url) {
            $this->url = Url::to('', TRUE);
        }

        $view = $this->getView();

        ShareAsset::register($view);
    }

    protected function parseTemplate($network) {
        if (in_array($network,$this->missButton)) {
            return false;
        }
        $url = $this->networks[$network];
        $btn_class = $network;
        $icon_class = $network;
        if (is_array($url)) {
            if (isset($url['btn_class'])) {
                $btn_class = $url['btn_class'];
            }
            if (isset($url['icon_class'])) {
                $icon_class = $url['icon_class'];
            }
            $url = $url['url'];
        }
        if ($this->type == 'small') {
            $button = str_replace('{button}', '<a href="javascript:void(0);" class="btn btn-social-icon btn-{btn_class}" onClick="sharePopup(\'' . $url . '\');">'
                    . '<i class="fa fa-{icon_class}"></i></a>', $this->template);
        } else {
            $button = str_replace('{button}', '<a href="javascript:void(0);" class="btn btn-block btn-social btn-{btn_class}" onClick="sharePopup(\'' . $url . '\');">'
                    . '<i class="fa fa-{icon_class}"></i> {text}</a>', $this->template);
        }
        $button = str_replace('{text}', $this->text, $button);
        $button = str_replace('{network}', $network, $button);
        $button = str_replace('{icon_class}', $icon_class, $button);
        $button = str_replace('{btn_class}', $btn_class, $button);
        $button = str_replace('{url}', ($this->url), $button);
        $button = str_replace('{title}', (Yii::$app->controller->view->title), $button);
        $button = str_replace('{via}', $this->via, $button);
        return $button;
    }

}
