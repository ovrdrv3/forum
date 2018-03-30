<?php

namespace app\Filters;

use Illuminate\Http\Request;
use App\User;
use App\Filters\Filters;


class ThreadFilters extends Filters
{
	protected $request; 
	protected $builder; 
	protected $filters = ['by'];
	
    public function by($username)
    {
        $user =  User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    	
    }
}
