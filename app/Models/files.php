<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class files extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'parent_id',
        'user_id',
        'type',
        'file_path',
        'file_size',
        'file_type'
    ];



    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */

    public function children(): HasMany
    {
        return $this->hasMany(files::class, 'parent_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(files::class, 'parent_id', 'id')
            ->withDefault([
                'name' => 'No Parent'
            ]);
    }

//    public function children()
//    {
//        return $this->hasMany(files::class, 'parent_id', 'id');
//    }
}
