@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create a new thread?</div>
                <div class="panel-body">
                    <form method="POST" action="/threads">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <select name="channel_id" id="channel_id" class="form-control" required>
                                <option value="">Choose One...</option>
                                @foreach ($channels as $channel)
                                    <option value="{{ $channel->id }}" {{ $channel->id == old('channel_id') ? 'selected' : '' }}>{{$channel->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input name="title" id="title" class="form-control" value="{{ old('title') }}" required></input>
                        </div>
                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" placeholder="Have something to say?" rows="8">{{ old('body')}}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" required>Publish</button>
                        <div class="form-group">
                            @if (count($errors))
                                <ul class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        <br>
                                    @endforeach
                                </ul>    
                            @endif  
                        </div>
                    </form>
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
