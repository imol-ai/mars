@extends('layouts.app')

@section('title')
<a href="{{ route('sittings.index') }}" class="breadcrumb" style="cursor: pointer">@lang('voting.assembly')</a>
<a href="#!" class="breadcrumb" style="cursor: pointer">{{ $sitting->title }}</a>
@endsection
@section('student_council_module') active @endsection

@section('content')

<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">{{ $sitting->title }}
                    <span class="right">
                        @livewire('passcode')
                    </span>
                </span>
                <table>
                    <tbody>
                        <tr>
                            <th scope="row">@lang('voting.opened_at')</th>
                            <td>{{ $sitting->opened_at }}</td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('voting.closed_at')</th>
                            <td>{{ $sitting->closed_at }}
                            @if($sitting->isOpen())
                            @can('administer', $sitting)
                                <form action="{{ route('sittings.close', $sitting->id) }}" method="POST">
                                    @csrf
                                    <x-input.button text="voting.close_sitting" class="red" />
                                </form>
                            @endcan
                            @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">@lang('voting.questions')</span>
                <table>
                    <thead>
                    <tr>
                        <th>@lang('voting.question_title')</th>
                        <th>@lang('voting.opened_at')</th>
                        <th>@lang('voting.closed_at')</th>
                        <th>
                            @if($sitting->isOpen())
                            @can('administer', $sitting)
                            <x-input.button href="{{ route('questions.create', ['sitting' => $sitting]) }}" floating class="right" icon="add" />
                            @endcan
                            @endif
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($sitting->questions()->orderByDesc('opened_at')->get() as $question)
                    <tr>
                        <td>{{$question->title}}</td>
                        <td>{{$question->opened_at}}</td>
                        <td>
                            {{$question->closed_at}}
                            @if($question->isOpen())
                            @can('administer', $sitting)
                            <form action="{{ route('questions.close', $question->id) }}" method="POST">
                                @csrf
                                <x-input.button text="voting.close_question" class="red" />
                            </form>
                            @endcan
                            @endif
                        </td>
                        <td>
                            @can('vote', $question)
                            <x-input.button href="{{ route('questions.show', $question->id) }}" floating class="right" icon="thumbs_up_down" />
                            @elsecan('viewResults', $question)
                            <x-input.button href="{{ route('questions.show', $question->id) }}" floating class="right" icon="remove_red_eye" />
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection