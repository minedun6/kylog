<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>{{ app_name() }} | Login</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="Preview page of Metronic Admin Theme #4 for " name="description"/>
    <meta content="" name="author"/>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/global/css/components-md.min.css') }}" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="{{ asset('assets/global/css/plugins-md.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/pages/css/login.min.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .form-actions {
            border-bottom: 0 !important;
        }
    </style>
    <link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->

<body class=" login">
<div class="content" style="position: relative; top:50px;">
    <!-- BEGIN LOGIN FORM -->
    {{ Form::open(['route' => 'frontend.auth.login', 'class' => 'login-form']) }}
    <div class="logo">
        <a href="index.html">
            <img src="img/app/kylog.png" style="margin-left: -40px;position: relative; top: -35px;" alt=""/>
        </a>
    </div>
    @include ('includes.partials.messages')
    <div class="form-group">
        <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
        <label class="control-label visible-ie8 visible-ie9">{{ trans('validation.attributes.frontend.email') }}</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off"
               placeholder="{{ trans('validation.attributes.frontend.email') }}" name="email"/></div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">Password</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"
               placeholder="Password" name="password"/></div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-6">
                <label class="rememberme check mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" name="remember" value="1"/>{{ trans('labels.frontend.auth.remember_me') }}
                    <span></span>
                </label>
            </div>
            <div class="col-md-6">
                <a href="javascript:;" id="forget-password" style="margin-top: 0;" class="forget-password">Forgot
                    Password?</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <button type="submit"
                        data-disable-with="<i class='fa fa-refresh fa-spin fa-fw'></i> Processing ..."
                        class="btn btn-block bg-blue-dark bg-font-blue-dark uppercase">
                    Login
                </button>
            </div>
        </div>
    </div>
{{ Form::close() }}
<!-- END LOGIN FORM -->
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="copyright"> 2017 © Peaksource.</div>
    </div>
</div>
<!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script>
<script src="assets/global/plugins/ie8.fix.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="vendor/rails/rails.js"></script>
<!-- END CORE PLUGINS -->
</body>
</html>