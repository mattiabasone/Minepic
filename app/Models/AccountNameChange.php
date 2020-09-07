<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AccountsNameChange.
 *
 * @property int                 $id
 * @property string              $uuid
 * @property string              $prev_name
 * @property string              $new_name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AccountNameChange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountNameChange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountNameChange query()
 * @mixin \Eloquent
 */
class AccountNameChange extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'accounts_name_change';

    protected $fillable = [
        'uuid',
        'prev_name',
        'new_name',
    ];
}
