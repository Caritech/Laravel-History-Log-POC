<?php

namespace App\Models;


trait HistoryTraits
{

    //boot[hook_class_name]
    public static function bootHistoryTraits()
    {
        static::observe(new ModelHistoryObserver);
    }
}

/*
    Use For Traits Booting function only
*/
class ModelHistoryObserver
{
    public function updated($model)
    {
        $dirty = self::getChangeText($model, 'Update');
        self::insertLog($model, $dirty);
    }

    public function created($model)
    {
        $dirty = self::getChangeText($model, 'Insert');
        self::insertLog($model, $dirty);
    }


    public function insertLog($model, $dirty)
    {
        \DB::table('history_logs')
            ->insert([
                'table_name' => $model->getTable(),
                'changes' => $dirty,
                'created_at' => now(),
                'by_name' => auth()->user()->name
            ]);
    }

    //FUNCTION TO GET CHANGE TEXT
    public function getChangeText($model, $task)
    {
        $changes = [];
        $change_txt = '';
        $dirty = $model->getDirty($model);

        if ($task == 'Delete') {
            foreach ($model->toArray() as $key => $value) {
                $changes[] = "[$key => $value]";
            }
        } else {;
            foreach ($dirty as $key => $value) {
                $original = $model->getOriginal($key);

                if (is_object($original)) {
                    $new_value = json_decode($value, true);

                    foreach ($original as $ori_key => $ori_val) {

                        $json_value = $new_value[$ori_key];

                        if ($new_value[$ori_key] != $ori_val) {
                            $changes[] = "$ori_key [$ori_val => $json_value]";
                        }
                    }
                } else {
                    $changes[] = "$key [$original => $value]";
                }
            }
        }

        foreach ($changes as $v) {
            $change_txt .= $v . "\n";
        }

        return $change_txt;
    }
}
