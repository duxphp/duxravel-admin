<?php

namespace Modules\Tools\UI;

use Duxravel\Core\UI\Form\Component;
use Duxravel\Core\UI\Form\Element;
use Duxravel\Core\UI\Form\Text;
use Modules\System\Events\MenuUrl;

/**
 * Class UrlSelect
 * url选择器
 * @package Duxravel\Core\UI\Form
 */
class UrlSelect extends Element implements Component
{

    protected Text $object;
    private String $url;

    /**
     * Text constructor.
     * @param string $name
     * @param string $field
     * @param string $has
     */
    public function __construct(string $name, string $field, string $url = '', string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->has = $has;
        $this->url = $url;
        $this->object = new Text($this->name, $this->field, $this->has);
    }

    /**
     * 渲染组件
     * @param $value
     * @return string
     */
    public function render(): array
    {
        $data = $this->getMenuUrl();

        $tab = [];
        foreach ($data as $key => $vo) {
            $tab[] = [
                'name' => $vo['name'],
                'key' => $key
            ];
        }
        $tab = json_encode($tab);
        $field = $this->getModelField();

        return $this->object->afterText([
            'nodeName' => 'div',
            'vData' => [
                'init' => ['open' => false],
            ],
            'child' => [
                [
                    'nodeName' => 'div',
                    'child' => '选择',
                    'vOn:click' => <<<JS
                      window.appDialogTable({
                        multiple: false,
                        column: [
                          {
                            name: '#',
                            key: 'id'
                          },
                          {
                            name: '名称',
                            key: 'title'
                          },
                        ],
                        type: $tab,
                        url: '{$this->url}',
                        callback: (item) => {
                          $field = item.url
                        }
                      })
                    JS
                ],
            ]
        ])->getRender();

    }

    /**
     * @return array
     */
    public static function getMenuUrl(): array
    {
        $list = event(new MenuUrl);
        $data = [];
        foreach ((array)$list as $value) {
            $data = array_merge_recursive((array)$data, (array)$value);
        }
        return array_filter($data);
    }

}
