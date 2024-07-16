<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\user;
use App\Policies\V1\UserPolicy;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;


class UserController extends ApiController
{
    protected $policyClass = UserPolicy::class;

    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(User::filter($filters)->paginate());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        if ($request->user()->is_manager)
            return new UserResource(User::create($request->mappedAttributes()));
        else return $this->error('You are not authorized to update that resource', 403);

    }

    /**
     * Display the specified resource.
     */
    public
    function show(User $user)
    {
        if ($this->include('tickets')) {
            return new UserResource($user->load('tickets'));
        }
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public
    function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(UpdateUserRequest $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            //  Log::info('Received user_id:', ['user_id' => $user_id]);

            //Policy
            if ($request->user()->is_manager) {
                $user->update($request->mappedAttributes());
                return new UserResource($user);
            } else return $this->error('You are not authorized to update that resource', 403);
        } catch (ModelNotFoundException) {
            return $this->error('User cannot be found', 404);
        }
    }

    public
    function replace(ReplaceUserRequest $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);

            //Policy
            if ($request->user()->is_manager) {
                $this->isAble('replace', $user);
                $user->update($request->mappedAttributes());
                return new UserResource($user);
            }
        } catch (ModelNotFoundException) {
            return $this->error('User cannot be found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            if (Auth::user()->is_manager) {
                $user->delete();
                return $this->ok("user successfully deleted");
            } else $this->error('User cannot be found', 404);
        } catch (ModelNotFoundException) {
            return $this->error('user cannot be found', 404);
        }

    }


}
