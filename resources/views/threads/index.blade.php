@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Forum Threads</div>

                <div class="panel-body">
                	@foreach ($threads as $thread)
                		<article>
                			<h4>
                                <a href="{{$thread->path()}}">
                                {{ $thread->title }}
                                </a>
                            </h4>
                			<div class="body">
                				{{ $thread->body }}
                			</div>
                		</article>
                        <hr>
                	@endforeach

                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
