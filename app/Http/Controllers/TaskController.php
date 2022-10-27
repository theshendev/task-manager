<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks    = Task::orderBy('priority', 'asc')->get();
        $projects = Project::get();
        return view('tasks.index', compact('tasks','projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::all();

        return view('tasks.create',compact('projects'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $data = $request->validated();

        // Get the maximum priority number and if there is not any task, then its value must be 0
        $lastPriority = Task::max('priority') ?: 0;

        $data['priority'] = ++$lastPriority;

        Task::create($data);

        return redirect()->route('tasks.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $projects = Project::all();
        return view('tasks.edit',compact('task','projects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaskRequest $request
     * @param  \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index');
    }
    public function updatePriority(Request $request)
    {

        $task = Task::findOrFail($request->task_id);
        $prev = $request->prev_id ? Task::find($request->prev_id) : null;
        $next = $request->next_id ? Task::find($request->next_id) : null;

        // If there is no previous task, this task is the first one so we make this priority number one
        if (!$prev)
        {
            $priority = 1;
        }
        // If there is no next task, this task is the last one so we make this the last priority

        else if (!$next)
        {
            $priority = Task::max('priority');

        }
        // If this task's priority is less than its previous one, it means that we have to give the previous one's priority to this one and if not we increment it
        else
        {
            $priority = $task->priority < $prev->priority ? $prev->priority : $prev->priority + 1;
        }
        // When we took care of the rearranged task, we have to change others as well
        // We have to decrement tasks' priority which were more than this one's and now are less than or equal to the new priority

        Task::where('priority', '>', $task->priority)
            ->where('priority', '<=', $priority)
            ->update(['priority' => DB::raw('priority - 1')]);


       // We have to increment tasks' priority which were less than this one's and now are more than or equal to the new priority

        Task::where('priority', '<', $task->priority)
            ->where('priority', '>=', $priority)
            ->update(['priority' => DB::raw('priority + 1')]);

        $task->priority = $priority;
        $task->save();

        return response()->json('',200);

    }
}
