<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\AuthorFilter;
use App\Http\Filters\V1\QueryFilter;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\TicketResource;
use App\Http\Resources\V1\UserResource;
use App\Models\Ticket;
use App\Models\user;
use Illuminate\Database\Eloquent\Builder;
use phpDocumentor\Reflection\Types\Collection;

class AuthorController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(User::has('tickets')->filter($filters)->paginate());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
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
    public function update(UpdateuserRequest $request, user $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(user $user)
    {
        //
    }


}
