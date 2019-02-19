<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class GlobalSettings extends Model
{
    protected $settings;
    protected $keyValuePair;

    public function __construct($settings)
    {
        $this->settings = $settings;
        foreach ($settings as $setting){
            $this->keyValuePair[$setting->key] = $setting->value;
        }
    }

    public function has(string $key){ /* check key exists */ }
    public function contains(string $key){ /* check value exists */ }
    public function get(string $key){ /* get by key */ }
}
