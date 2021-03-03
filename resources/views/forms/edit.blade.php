@extends('layouts.main')

@section('content')
<main id="js-page-content" role="main" class="page-content mt-3">
        @include('includes.messages')   
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-plus-circle'></i> Редактировать
            </h1>

        </div>
        <form action="{{ route('user.commoninfo.update', ['id' => $user->id]) }}" method="post">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Общая информация</h2>
                            </div>
                            <div class="panel-content">
                                <!-- username -->
                                <div class="form-group">
                                    <label class="form-label" for="name">Имя</label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}">
                                </div>

                                <!-- title -->
                                <div class="form-group">
                                    <label class="form-label" for="job">Место работы</label>
                                    <input type="text" id="job" name="job" class="form-control" value="{{ $user->job }}">
                                </div>

                                <!-- tel -->
                                <div class="form-group">
                                    <label class="form-label" for="phone">Номер телефона</label>
                                    <input type="text" id="phone" name="phone" class="form-control" value="{{ $user->phone }}">
                                </div>

                                <!-- address -->
                                <div class="form-group">
                                    <label class="form-label" for="address">Адрес</label>
                                    <input type="text" id="address" name="address" class="form-control" value="{{ $user->address }}">
                                </div>
                                <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                    <button class="btn btn-warning" type="submit">Редактировать</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection

  