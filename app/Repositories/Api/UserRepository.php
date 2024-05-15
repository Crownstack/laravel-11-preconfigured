<?php

namespace App\Repositories\Api;

use App\Events\UserSignup;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Http\Resources\UserSignupResource;

class UserRepository extends BaseRepository
{
        
    /**
     * Method to create user and dispatch the UserSignup event 
     *
     * @param  array $data
     * @return array
     */
    public function userSignup($data)
    {
       $user =  User::create($data);

       UserSignup::dispatch($user);

       return $this->returnData(responseType:'ok',responseCode:'USER_CREATED',data:new UserSignupResource($user));
    }

}
