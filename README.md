# Laravel History Using Traits

## This function is use enable logging function when performing UPDATE & CREATE on Laravel Eloquent

### Files required
- app/Models/ExampleModel.php
- app/Traits/HistoryTraits.php


### How to use:
```
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HistoryTraits; // Add This Line

class ExampleModel extends Model
{
    use HistoryTraits; //Add This Line
    ...
}
```

### This is the insert log table & column
you may need to update it to match your "history_logs" table structure
```
\DB::table('history_logs')
->insert([
    'table_name' => $model->getTable(),
    'changes' => $dirty,
    'created_at' => now(),
    'by_name' => auth()->user()->name
]);
```