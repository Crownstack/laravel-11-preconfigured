<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiBaseController;
use App\Repositories\Api\AuthRepository;
use App\Http\Requests\Api\UserSignupFormRequest;
use App\Repositories\Api\UserRepository;

class UserController extends ApiBaseController
{
    /** To load the user repository and make it available in the class */
    public $userRepository;
    
    /**
     * Create a new instance of UserController  
     *
     * @param  UserRepository $userRepository
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    
    /**
     * Method for user signup 
     *
     * @param  UserSignupFormRequest $request
     * @return array 
     */
    public function signup(UserSignupFormRequest $request)
    {
        $modelResponse =  $this->userRepository->userSignup($request->all());
        return $this->sendApiResponse($modelResponse);
    }
}
