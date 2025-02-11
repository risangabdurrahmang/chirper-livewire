<?php

namespace App\Livewire;

use App\Models\Task as ModelsTask;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Task extends Component
{
    #[Validate('required|string|min:3|max:30')]
    public string $title;

    #[Validate('required|string|min:3|max:255')]
    public string $description;

    #[Validate('required|in:pending,in_progress,completed')]
    public string $status;

    public $taskId;

    // public function createTask()
    // {
    //     $this->validate();
    //     ModelsTask::create([
    //         'user_id' => Auth::id(),
    //         'title' => $this->title,
    //         'description' => $this->description,
    //         'status' => $this->status,
    //     ]);
    //     $this->reset();
    // }

    public function createOrUpdate()
    {
        $this->validate();
        ModelsTask::updateOrCreate([
            'id' => $this->id ?? $this->taskId,
        ], [
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
        ]);
        $this->reset();
    }

    public function editTask($id)
    {
        $task = ModelsTask::find($id);
        $this->taskId = $task->id;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->status = $task->status;
    }

    public function deleteTask($id)
    {
        ModelsTask::find($id)->delete();
    }

    #[On('echo:task-created,TaskCreate')]
    #[On('echo:task-updated,TaskUpdate')]
    #[On('echo:task-deleted,TaskDelete')]
    public function listenForEvent()
    {
        $this->dispatch('$refresh');
    }

    public function render()
    {
        // $query = ModelsTask::query();
        // $users = User::query()
        //     ->whereIn('id', (clone $query)
        //         ->select('user_id')->distinct())
        //     ->pluck('name', 'id');
        // $tasks = (clone $query)
        //     ->with('user')
        //     ->select('id', 'user_id', 'title', 'description', 'status')
        //     ->toBase()
        //     ->lazyById(10000, 'id');

        // $tasks = ModelsTask::query()
        //     ->with('user')
        //     ->chunk(500, function ($tasks) {
        //         foreach ($tasks as $task) {
        //             $task->id;
        //             $task->user_id;
        //             $task->title;
        //             $task->description;
        //             $task->status;
        //         }
        //     });

        return view('livewire.task', [
            'tasks' => Cache::remember('tasks', now()->addMinutes(1), function () {
                ModelsTask::with('user')->latest()->get();
            })
        ]);
    }
}
