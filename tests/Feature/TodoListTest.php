<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    //this run migrate command to create and run all tables
    //may be clean up the database
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    private $list;
    //this function runs before any test function in this class its like construct
    public function setUp(): void
    {
        parent::setUp();
        $this->list =  TodoList::factory()->create(['name' => 'my list']);
    }

    public function test_fetch_all_todo_list()
    {
        //preparation / prepare
        //-----whith this we can create duplicate records
        // TodoList::factory()->count(2)->create(['name' => 'my list']);


        //action / perform
        $response = $this->getJson(route('todo-list.index'));

        //assertion /predict
        $this->assertEquals(1, count($response->json()));
        $this->assertEquals('my list', $response->json()[0]['name']);
    }




    public function test_fetch_single_todo_list()
    {

        //preparation / prepare
        // $list = TodoList::factory()->create(); //replaces with setUp
        //action / perform
        $response = $this->getJson(route('todo-list.show',  $this->list->id))->assertOk()->json();
        //assertion /predict
        // $response->assertStatus(200);
        //$response->assertOk();

        //we can do also
        // $response = $this->getJson(route('todo-list.show',  $list->id))->assertOk()->json();


        $this->assertEquals($response['name'],  $this->list->name);
    }


    public function test_store_new_todo_list()
    {
        //prepare
        //make() will create  a record without storing it in database
        $list = TodoList::factory()->make();
        //action
        $response = $this->postJson(route('todo-list.store', ['name' => $list->name]))->assertCreated()->json();


        //assertion
        // parameters aretable name and field record value
        $this->assertEquals($list->name, $response['name']);
        $this->assertDatabaseHas('todo_lists', ['name' => $list->name]);
    }


    public function test_while_storing_todo_list_name_field_is_required()
    {
        //prepare

        //action
        $this->withExceptionHandling();
        $response = $this->postJson(route('todo-list.store'))->assertUnprocessable()->assertJsonValidationErrors(['name']);
    }

    public function test_delete_todo_list()
    {

        $this->deleteJson(route('todo-list.destroy', $this->list->id))->assertNoContent();

        $this->assertDatabaseMissing('todo_lists', ['name' => $this->list->name]);
    }

    public function test_update_todo_list()
    {
        //prepare

        //assertion
        $response = $this->patchJson(route('todo-list.update', $this->list->id), ['name' => 'updated name'])->assertOk();

        $this->assertDatabaseHas('todo_lists', ['id' => $this->list->id, 'name' => 'updated name']);
    }

    public function test_while_updating_todo_list_name_field_is_required()
    {
        //prepare

        //action
        $this->withExceptionHandling();
        $response = $this->patchJson(route('todo-list.update', $this->list->id))->assertUnprocessable()->assertJsonValidationErrors(['name']);
    }
}
