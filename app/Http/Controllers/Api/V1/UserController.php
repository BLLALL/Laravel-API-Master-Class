<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\TicketResource;
use App\Http\Resources\V1\UserResource;
use App\Models\Ticket;
use App\Models\user;
use App\Policies\V1\UserPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

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
        try {
            $this->isAble('store', User::class);
            return new UserResource(User::create($request->mappedAttributes()));
        } catch (AuthorizationException $e) {
            Log::error('User creation failed: ' . $e->getMessage());
            return $this->error("You're not authorized to create that resource"  . $e->getMessage(), 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if ($this->include('tickets')) {
            return new UserResource($user->load('tickets'));
        }
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);
          //  Log::info('Received user_id:', ['user_id' => $user_id]);

            //Policy
            $this->isAble('update', $user);
            $user->update($request->mappedAttributes());
            return new UserResource($user);

        } catch (ModelNotFoundException) {
            return $this->error('User cannot be found', 404);
        } catch (AuthorizationException) {
            return $this->error('You are not authorized to update that resource', 403);
        }

    }

    public function replace(ReplaceUserRequest $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);

            //Policy
            $this->isAble('replace', $user);
            $user->update($request->mappedAttributes());

            return new UserResource($user);

        } catch (ModelNotFoundException) {
            return $this->error('User cannot be found', 404);
        } catch (AuthorizationException) {
            return $this->error('You are not authorized to replace that resource', 403);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $this->isAble('delete', $user);
            $user->delete();

            return $this->ok("user successfully deleted");
        } catch (ModelNotFoundException) {
            return $this->error('user cannot be found', 404);
        }

    }


}
