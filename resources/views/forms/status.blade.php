@extends('layouts.main')

@section('content')
    <main id="js-page-content" role="main" class="page-content mt-3">
        @include('includes.messages')
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-sun'></i> Установить статус
            </h1>

        </div>

        @php
            $statuses = [
                'Онлайн' => 'online',
                'Отошёл' => 'away',
                'Не беспокоить' => 'busy'
            ];
        @endphp
        <form action="{{ route('user.status.update', ['id' => $id]) }}" method="post">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Установка текущего статуса</h2>
                            </div>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- status -->
                                        <div class="form-group">
                                            <label class="form-label" for="status">Выберите статус</label>
                                            <select class="form-control" name="status" id="status">
                                                @foreach($statuses as $statusName => $statusValue)
                                                <option value="{{ $statusValue }}" @if($statusValue == $currentStatus) selected @endif>{{ $statusName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                        <button class="btn btn-warning" type="submit">Set Status</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection
