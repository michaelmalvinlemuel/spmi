<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectForm extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function projects() {
        return $this->morphMany('App\ProjectNode', 'project');
    }

    public function forms() {
    	return $this->hasMany('App\ProjectFormItem');
    }
}
