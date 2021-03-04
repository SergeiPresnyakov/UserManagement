@extends('layouts.main')

@section('content')
    <main id="js-page-content" role="main" class="page-content mt-3">
        @include('includes.messages')
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-image'></i> Загрузить аватар
            </h1>

        </div>
        <form action="{{ route('user.avatar.update', ['id' => $id]) }}" enctype="multipart/form-data" method="post">
            @csrf
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Текущий аватар</h2>
                            </div>
                            <div class="panel-content">
                                <div class="form-group">
                                    <img src="{{ asset($avatar) }}" alt="User avatar" class="img-responsive" width="200">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="avatar">Выберите аватар</label>
                                    <input type="file" id="avatar" name="avatar" class="form-control-file">
                                </div>


                                <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                    <button class="btn btn-warning">Загрузить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection
