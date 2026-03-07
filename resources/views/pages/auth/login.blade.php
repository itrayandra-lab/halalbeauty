@extends('layouts.admin.auth')
@section('content')
    <div class="panel-body">
        <h2 class="text-center m-t-0 m-b-30 text-charcoal">
            <b>Halal Beauty</b>
        </h2>
        <h4 class="text-muted text-center m-t-0">Sign In Portal</h4>
        @include('components.alert-basic')
        <form class="form-horizontal m-t-20" action="{{ route('portal.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <div class="col-xs-12">
                    <input class="form-control" type="email" name="email" required="" placeholder="Email" style="border-color: #FFDAB9;">
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <input class="form-control" type="password" name="password" required="" placeholder="Password" style="border-color: #FFDAB9;">
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <div class="checkbox checkbox-primary">
                        <input id="checkbox-signup" name="remember-me" type="checkbox">
                        <label for="checkbox-signup" class="text-charcoal">
                            Remember me
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group text-center m-t-20">
                <div class="col-xs-12">
                    <button class="btn btn-fresh-glow btn-block waves-effect waves-light" type="submit">
                        <i class="fa fa-lock"></i> Log In
                    </button>
                </div>
            </div>


            <div class="form-group m-t-30 m-b-0">
                <div class="col-sm-7">
                    <a href="/" class="text-muted"><i class="fa fa-long-arrow-left m-r-5"></i> Back To Landing
                        Page</a>
                </div>

            </div>
        </form>
    </div>
@endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap');

    html, body {
        background: linear-gradient(135deg, #E0FFF0 0%, #FFDAB9 100%) !important;
        height: 100% !important;
        min-height: 100vh !important;
        margin: 0 !important;
        padding: 0 !important;
        font-family: "Raleway", sans-serif !important;
    }
    .wrapper-page {
        margin: 0 auto !important;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }
    .panel-pages {
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border: none;
        background: rgba(255, 255, 255, 0.9);
        width: 100%;
        max-width: 450px;
    }
    .form-control {
        font-family: "Raleway", sans-serif;
    }
    .form-control:focus {
        border-color: #FFDAB9 !important;
        box-shadow: 0 0 0 2px rgba(255, 218, 185, 0.2) !important;
    }
    .btn-fresh-glow {
        font-family: "Raleway", sans-serif;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    h2, h4 {
        font-family: "Raleway", sans-serif;
    }
</style>
@endpush
