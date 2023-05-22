<?php

namespace App\Models\Backend\Setting;

use App\Models\Backend\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingModel extends Model {
    protected $table = 'setting';

    public function edit($code, $data) {
        DB::table($this->table)
            ->where('code', $code)
            ->delete();

        foreach ($data as $key => $value) {
            if (substr($key, 0, strlen($code)) == $code) {
                $serialized = is_array($value) ? 1 : 0;
                $valueData = is_array($value) ? json_encode($value, true) : $value;
                DB::table($this->table)
                    ->insert([
                        'code' => $code,
                        'key' => $key,
                        'value' => $valueData,
                        'serialized' => $serialized
                    ]);
            }
        }
        $this->addLogging($this->table, __FUNCTION__);
        Cache::forget('setting');
    }

    public function delete($code) {
        DB::table($this->table)
            ->where('code', $code)
            ->delete();
        Cache::forget('setting');
    }

    public function getList($code) {
        $settingData = [];
        $query = DB::table($this->table)
            ->where('code', $code)
            ->get();
        foreach (format_array($query) as $result) {
            $settingData[$result['key']] = $result['serialized'] ? json_decode($result['value'], true) : $result['value'];
        }
        return $settingData;
    }

    public function editValue($code = '', $key = '', $value = '') {
        $serialized = is_array($value) ? true : false;
        $valueData = is_array($value) ? json_encode($value, true) : $value;
        DB::table($this->table)
            ->where([
                'code' => $code,
                'key' => $key,
            ])
            ->update([
                'value' => $valueData,
                'serialized' => $serialized
            ]);
        Cache::forget('setting');
    }
}
