<?php

namespace App\Http\Controllers;

use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AuthorTicketsController extends Controller
{
    public function index($author_id) {
        return TicketResource::collection(Ticket::where('user_id', $author_id)->filters()->paginate(10));
    }


}
