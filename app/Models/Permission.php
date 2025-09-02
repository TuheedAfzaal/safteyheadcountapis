<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'action_id',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function action()
    {
        return $this->belongsTo(Action::class);
    }
}
