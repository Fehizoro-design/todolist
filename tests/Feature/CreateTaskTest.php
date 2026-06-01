<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_task()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('pages::task-create')
            ->set('title', 'Ma nouvelle tâche')
            ->set('detail', 'Tous les details de ma nouvelle tâche')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'title' => 'Ma nouvelle tâche'
        ]);

        $this->actingAs($user)
            ->get(route('tasks.index'))
            ->assertSee('Ma nouvelle tâche');
    }
}
