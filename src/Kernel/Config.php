<?php

declare(strict_types=1);

namespace Hi\Kernel;

class Config
{
    protected $data = [
        'application' => [
            'name'        => 'hi-framework',
            'module'      => '',
            'description' => '轻量级服务端框架',
            'version'     => '0.0.0',
            'debug'       => 'false',
        ],
    ];

    public function get($key, $defaultValue = null)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        $point = $this->data;
        foreach (explode('.', $key) as $segment) {
            $point = $point[$segment] ?? [];
            if (!$point) {
                break;
            }
        }

        if ($point) {
            return $point;
        }

        return $defaultValue;
    }

    public function merge(array $data)
    {
        $this->data = array_merge($this->data, $data);
    }
}
