<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    //

    public function index(TodoList $todo_list)
    {

        //$tasks = Task::where(['todo_list_id' => $todo_list->id])->get();

        //instead of doing the above line 
        $tasks = $todo_list->tasks;
        return response($tasks);
    }

    public function store(TaskRequest $request, TodoList $todo_list)
    {


        // $request['todo_list_id'] = $todo_list->id;
        // $task = Task::create($request->all());

        //instead of the top 2 lines since we have relationship we can store it like this
        $task = $todo_list->tasks()->create($request->validated());
        return $task;
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    public function update(Task $task, Request $request)
    {
        $task->update($request->all());
        return response($task);
    }
}
