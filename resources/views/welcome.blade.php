@extends('layouts.app')

@section('styles')
    <style>
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col text-center"><h4 class="mb-0">Sections</h4></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col"><a href="{{route('tasks.index')}}" class="btn btn-lg btn-primary w-100">Tasks</a></div>
                            <div class="col"><a href="{{route('projects.index')}}" class="btn btn-lg btn-success w-100">Projects</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

