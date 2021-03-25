<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExampleModel extends Model
{
    use HistoryTraits;

    public $table = "example_models";
    public $guarded = ['id'];
}
