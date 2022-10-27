@extends('layouts.app')

@section('styles')
    <style>
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="mb-4"><a class="btn btn-success text-white" href="{{ route('tasks.create') }}">Create New Task</a></div>

                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col"><h4 class="mb-0">Tasks</h4></div>
                            <div class="col-auto">
                                <select class="form-control" name="projects">
                                    <option value="">- All Projects -</option>
                                    @foreach( $projects as $project )
                                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if( $tasks->count() > 0 )
                            <ul class="list-group tasks" id="sortable">
                                @foreach( $tasks as $task )
                                    <li class="list-group-item" data-task-id="{{ $task->id }}" data-project-id="{{ $task->project ? $task->project->id : '' }}">
                                        <div class="row align-items-center">
                                            <div class="col">{{ $task->task_name }}</div>
                                            <div class="col-auto">{{ $task->project ? $task->project->project_name : 'Not related to any project' }}</div>
                                            <div class="col-auto pr-0"><a class="btn btn-info text-white" href="{{ route('tasks.edit', $task->id) }}">Edit</a></div>
                                            <div class="col-auto">
                                                <form class="mb-0 delete" action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>There is no tasks yet, <a href="{{ route('tasks.create') }}">Create the first one.</a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $("#sortable").sortable({
            stop: function( event, ui ) {
                var $e        = $(ui.item);
                var $prevItem = $e.prev();
                var $nextItem = $e.next();
                $.ajax({
                    url: "{{ route('tasks.updatePriority') }}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        task_id: $e.data('task-id'),
                        prev_id: $prevItem ? $prevItem.data('task-id') : null,
                        next_id: $nextItem ? $nextItem.data('task-id') : null
                    }
                });
            }
        });
        $('[name="projects"]').on('change', function(){
            var $this = $(this);

            if( $this.val() ){
                $('.tasks li').hide();
                $('.tasks li')
                    .filter( $(`[data-project-id="${$this.val()}"]`) )
                    .show();
                return;
            }
            $('.tasks li').show();
        });
    </script>
    <script>
        $(document).ready(function(){
            $('form.delete').on('submit', function(){
               confirm("Do you really want to detele this task?")
            });
        });
    </script>
@endsection
