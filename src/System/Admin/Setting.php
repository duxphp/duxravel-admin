<?php

namespace Modules\System\Admin;

use Illuminate\Support\Collection;
use \Duxravel\Core\UI\Widget;
use \Duxravel\Core\UI\Form;

class Setting extends \Modules\System\Admin\Expend
{

    public function handle(): string
    {
        return $this->form()->render();
    }

    public function form(): Form
    {
        //$this->dispatch(new \Duxravel\Core\Jobs\Task(\Modules\System\Service\Menu::class, 'test', [], 20));

        $environment = app()->environment();
        $data = collect([
            'APP_NAME' => env('APP_NAME'),
            'APP_URL' => env('APP_URL'),
            'APP_DEBUG' => env('APP_DEBUG'),
            'LOG_CHANNEL' => env('LOG_CHANNEL'),
            'LOG_LEVEL' => env('LOG_LEVEL'),
            'BROADCAST_DRIVER' => env('BROADCAST_DRIVER'),
            'CACHE_DRIVER' => env('CACHE_DRIVER'),
            'QUEUE_CONNECTION' => env('QUEUE_CONNECTION'),
            'SESSION_DRIVER' => env('SESSION_DRIVER'),
            'SESSION_LIFETIME' => env('SESSION_LIFETIME'),
            'FILESYSTEM_DRIVER' => env('FILESYSTEM_DRIVER'),
            'IMAGE_DRIVER' => env('IMAGE_DRIVER'),
            'IMAGE_THUMB' => env('IMAGE_THUMB'),
            'IMAGE_THUMB_WIDTH' => env('IMAGE_THUMB_WIDTH'),
            'IMAGE_THUMB_HEIGHT' => env('IMAGE_THUMB_HEIGHT'),
            'IMAGE_WATER' => env('IMAGE_WATER'),
            'IMAGE_WATER_ALPHA' => env('IMAGE_WATER_ALPHA'),
            'IMAGE_WATER_IMAGE' => env('IMAGE_WATER_IMAGE'),
            'THEME_DEFAULT' => env('THEME_DEFAULT'),
            'THEME_TITLE' => env('THEME_TITLE'),
            'THEME_KEYWORD' => env('THEME_KEYWORD'),
            'THEME_DESCRIPTION' => env('THEME_DESCRIPTION'),
        ]);
        $form = new \Duxravel\Core\UI\Form($data, false);
        $form->title('系统设置', false);
        $form->action(route('admin.system.setting.save'));
        $form->layout(Widget::alert('系统设置选项为运维人员便捷使用，非专业人士或不清楚选项请勿随意修改，否则可能会导致系统崩溃', '安全提示', function ($alert) {
            $alert->type('warning');
        }));

        $tabs = $form->tab();
        $tabs->column('信息配置', function (Form $form) {
            $form->text('系统名称', 'APP_NAME');
            $form->text('系统域名', 'APP_URL');
        });

        $tabs->column('主题配置', function (Form $form) {
            $form->text('主题标题', 'THEME_TITLE');
            $form->text('主题关键词', 'THEME_KEYWORD');
            $form->text('主题描述', 'THEME_DESCRIPTION');
            $form->text('默认主题', 'THEME_DEFAULT');
        });

        $tabs->column('安全配置', function (Form $form) {
            $form->radio('调试模式', 'APP_DEBUG', [
                true => '开启',
                false => '关闭'
            ]);

            $data = \collect(config('logging.channels'))->map(function ($item, $key) {
                return $key;
            })->toArray();
            $form->select('默认日志频道', 'LOG_CHANNEL', $data);

            $form->select('默认日志等级', 'LOG_LEVEL', [
                'emergency' => 'emergency',
                'alert' => 'alert',
                'critical' => 'critical',
                'error' => 'error',
                'warning' => 'warning',
                'notice' => 'notice',
                'info' => 'info',
                'debug' => 'debug',
            ]);
        });
        $tabs->column('性能配置', function ($form) {
            $data = \collect(config('broadcasting.connections'))->map(function ($item, $key) {
                return $key;
            })->toArray();
            $form->select('默认广播驱动', 'BROADCAST_DRIVER', $data);

            $data = \collect(config('cache.stores'))->map(function ($item, $key) {
                return $key;
            })->toArray();
            $form->select('默认缓存驱动', 'CACHE_DRIVER', $data);

            $data = \collect(config('queue.connections'))->map(function ($item, $key) {
                return $key;
            })->toArray();
            $form->select('默认队列驱动', 'QUEUE_CONNECTION', $data);

            $form->select('默认 SESSION 驱动', 'SESSION_DRIVER', [
                'file' => 'file',
                'cookie' => 'cookie',
                'database' => 'database',
                'apc' => 'apc',
                'memcached' => 'memcached',
                'redis' => 'redis',
                'dynamodb' => 'dynamodb',
                'array' => 'array',
            ]);
            $form->text('SESSION 生命周期', 'SESSION_LIFETIME')->type('number')->afterText('分钟');

        });

        $tabs->column('上传配置', function ($form) {
            $data = \collect(config('filesystems.disks'))->map(function ($item, $key) {
                return $key;
            })->toArray();
            $form->select('上传驱动', 'FILESYSTEM_DRIVER', $data);
            $form->radio('图片驱动', 'IMAGE_DRIVER', [
                'gd' => 'gd',
                'imagick' => 'imagick',
            ]);
            $form->radio('缩图裁剪', 'IMAGE_THUMB', [
                '' => '默认关闭',
                'center' => '居中裁剪缩放',
                'fixed' => '固定尺寸',
                'scale' => '等比例缩放',
            ]);
            $form->text('缩图宽度', 'IMAGE_THUMB_WIDTH')->type('number')->afterText('像素');
            $form->text('缩图高度', 'IMAGE_THUMB_HEIGHT')->type('number')->afterText('像素');
            $form->select('水印位置', 'IMAGE_WATER', [
                0 => '默认关闭',
                1 => '左上角',
                2 => '上居中',
                3 => '右上角',
                4 => '左居中',
                5 => '居中',
                6 => '右居中',
                7 => '左下角',
                8 => '下居中',
                9 => '右下角',
            ]);
            $form->text('水印透明度', 'IMAGE_WATER_ALPHA')->type('number')->afterText('%');
            $form->text('水印路径', 'IMAGE_WATER_IMAGE')->beforeText('resources/');
        });
        return $form;
    }

    public function save()
    {
        $data = $this->form()->save()->toArray();
        $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';
        $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));
        $contentArray->transform(function ($item) use ($data) {
            foreach ($data as $key => $vo) {
                if (str_contains($item, $key . '=')) {
                    return $key . '=' . $vo;
                }
            }
            return $item;
        });
        $content = implode("\n", $contentArray->toArray());
        \File::put($envPath, $content);
        return app_success('保存配置成功');
    }
}
