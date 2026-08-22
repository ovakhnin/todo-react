<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class TaskController extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    /**
     * Display a listing of the Tasks.
     */
    public function index(): InertiaResponse
    {
        Gate::authorize('viewAny', Task::class);

        /** @var \App\Models\User */
        $user = Auth::user();

        $tasks = $user?->tasks;

        return Inertia::render(
            'Task/List',
            ['tasks' => $tasks ? $tasks : []]
        );
    }

    /**
     * Show the form for creating a new Task.
     */
    public function create(): InertiaResponse
    {
        Gate::authorize('create', Task::class);

        return Inertia::render('Task/Create');
    }

    /**
     * Store a newly created Task in storage.
     */
    public function store(TaskStoreRequest $request): RedirectResponse
    {
        Gate::authorize('create', Task::class);

        $user = $request->user();
        $task = new Task($request->input());
        $task->user()->associate($user);
        $task->save();

        if ($task) {
            return Redirect::route('tasks.edit', $task->id);
        }

        return Redirect::route('tasks.index');
    }

    /**
     * Display the specified Task.
     */
    public function show(string $id): InertiaResponse|RedirectResponse
    {
        $task = Task::find($id);

        Gate::authorize('view', $task);

        if ($task) {
            return Inertia::render(
                'Task/Show',
                ['task' => $task]
            );
        }

        return Redirect::route('tasks.index');
    }

    /**
     * Show the form for editing the specified Task.
     */
    public function edit(string $id): InertiaResponse
    {
        $task = Task::find($id);

        Gate::authorize('view', $task);

        return Inertia::render(
            'Task/Edit',
            ['task' => $task ? $task : null]
        );
    }

    /**
     * Update the specified Task in storage.
     */
    public function update(TaskUpdateRequest $request, string $id): RedirectResponse
    {
        $task = Task::find($id);

        Gate::authorize('update', $task);

        if ($task) {
            $task->fill($request->validated());
            $task->save();
        }

        return Redirect::route('tasks.edit', $id);
    }

    /**
     * Remove the specified Task from storage.
     */
    public function destroy(string $id): RedirectResponse|Response
    {
        $task = Task::find($id);

        Gate::authorize('delete', $task);

        if ($task) {
            $task->delete();

            return response(
                [
                    'message' => 'Tasks has been deleted.',
                ],
                JsonResponse::HTTP_OK
            );
        }

        return Redirect::route('tasks.index');
    }

    /**
     * Toggle Completed status of the specified Task.
     */
    public function completed(string $id): RedirectResponse|Response
    {
        $task = Task::find($id);

        Gate::authorize('completed', $task);

        if ($task) {
            $task->is_completed = $task->is_completed ? false : true;
            $task->save();

            return response(
                [
                    'message' => 'Tasks status has been updated.',
                ],
                JsonResponse::HTTP_OK
            );
        }

        return Redirect::route('tasks.index');
    }
}
