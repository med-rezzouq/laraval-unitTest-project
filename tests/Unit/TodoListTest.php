<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_a_todo_list_can_have_many_tasks()
    {
        //we create unit test when we will not call controllers or route or database
        $list = $this->createTodoList();
        $task = $this->createTask(['todo_list_id' => $list->id]);

        $this->assertInstanceOf(Collection::class, $list->tasks);
        $this->assertInstanceOf(Task::class, $list->tasks->first());
    }
}
