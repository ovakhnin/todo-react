<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private $user = null;

    private $task = null;

    private function setTestData(): void
    {
        $this->user = User::factory()->create();

        $this->task = Task::factory()->create(
            [
                'user_id' => $this->user->id,
            ]
        );
    }


    public function test_tasks_page_is_displayed(): void
    {
        $this->setTestData();

        $response = $this
            ->actingAs($this->user)
            ->get('/tasks');

        $response->assertOk();
    }

    public function test_task_information_can_be_updated(): void
    {
        $this->setTestData();

        $response = $this
            ->actingAs($this->user)
            ->patch('/tasks/' . $this->task->id, [
                'name' => 'New Task Name',
                'description' => 'New Task Description',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect("/tasks/{$this->task->id}/edit");

        $this->task->refresh();

        $this->assertSame('New Task Name', $this->task->name);
        $this->assertSame('New Task Description', $this->task->description);
    }

    public function test_user_can_delete_their_task(): void
    {
        $this->setTestData();

        $response = $this
            ->actingAs($this->user)
            ->delete('/tasks/' . $this->task->id);

        $response
            ->assertSessionHasNoErrors();

        $this->assertNull($this->task->fresh());
    }

    public function test_user_cannot_delete_not_own_task(): void
    {
        $this->setTestData();

        /**
         * @var User
         */
        $badUser = User::factory()->create();

        $response = $this
            ->actingAs($badUser)
            ->delete('/tasks/' . $this->task->id);

        $response
            ->assertForbidden();

        $this->assertNotNull($this->task->fresh());
    }
}
