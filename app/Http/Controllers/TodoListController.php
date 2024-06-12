<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoList\StoreRequest;
use App\Http\Requests\TodoList\UpdatedRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todolist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Todolist::select('id', 'todo', 'completed')->where('user_id', Auth::user()->id)->get();
        return TodoResource::collection($todos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $todo = new Todolist();
        $todo->user_id = Auth::user()->id;
        $todo->todo = $request->todo;
        $todo->save();

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedRequest $request, string $id)
    {
        $todo = Todolist::find($id);
        if ($todo->user_id != Auth::user()->id) {
            return response()->json(['notOwned' => "You do not own this data."]);
        }
        $todo->completed = !$request->completed;
        $todo->save();

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = Todolist::find($id);
        if ($todo->user_id != Auth::user()->id) {
            return response()->json(['notOwned' => "You do not own this data."]);
        }
        $todo->delete();

        return response()->noContent();
    }
}
