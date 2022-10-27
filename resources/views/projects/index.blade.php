@extends('layouts.app')

@section('styles')
    <style>
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="mb-4"><a class="btn btn-success text-white" href="{{ route('projects.create') }}">Create New Project</a></div>

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col"><h4 class="mb-0">Projects</h4></div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if( $projects->count() > 0 )
                            <ul class="list-group">
                                @foreach( $projects as $project )
                                    <li class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col">{{ $project->project_name }}</div>
                                            <div class="col-auto pr-0"><a class="btn btn-info text-white" href="{{ route('projects.edit', $project->id) }}">Edit</a></div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>There is no projects yet, <a href="{{ route('projects.create') }}">Create the first one.</a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

