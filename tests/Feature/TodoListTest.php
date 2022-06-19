<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    //this run migrate command to create and run all tables
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fetch_todo_list()
    {
        TodoList::create(['name' => 'my list']);
        //preparation / prepare

        //action / perform
        $response = $this->getJson(route('todo-list.store'));


        //assertion /predict
        $this->assertEquals(1, count($response->json()));
    }
}
