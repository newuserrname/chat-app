<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://cdn.socket.io/4.5.4/socket.io.min.js" integrity="sha384-/KNQL8Nu5gCHLqwqfQjA689Hhoqgi2S84SNUxC3roTe4EhJ9AfLkp8QiQcU8AMzI" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js" 
            integrity="sha512-42PE0rd+wZ2hNXftlM78BSehIGzezNeQuzihiBCvUEB3CVxHvsShF86wBWwQORNxNINlBPuq7rG4WWhNiTVHFg==" 
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <style>
            .char-row {
                margin: 50px;
            }
            .char-user-list {
                list-style: none;
            }
            .imgLogo {
                width: 50px;
                height: 50px;
            }
            .name-image {
                width: 50px;
                height: 50px;
                border-radius: 50px;
                align-items: center;
                justify-content: center;
                display: flex;
                color: #fff;
            }
            .user-status-icon {
                position: absolute;
                color: grey;
                padding-left: 35px;
                margin-top: -10px;
            }
            .chat-image, .chat-name {
                display: inline-block;
            }
            .chat-user-list {
                margin-bottom: 10px;
                padding 5px;
            }
            .chat-user-list.active {
                background: #000,
            }
            .chat-header {
                min-height: 60px;
                padding: 10px;
                margin-bottom: 5px;
                background: #ffff
            }
            .chat-body {
                height: calc(100vh - 375px);
                background: floralwhite;
                margin-top: 10px;
            }
            .chat-box {
            }
            .message-text {
                margin-left: 60px;
            }
            .chat-input {
                border: 1px solid;
                padding: 10px 12px;
                border-radius: 10px;
                font-size: 15px;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Main -->
            <div class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                    <div class="col-md-9">
                        <img class="imgLogo" src="https://inkythuatso.com/uploads/images/2021/10/logo-messenger-inkythuatso-2-01-30-15-47-51.jpg"/>
                        <br>
                        <p class="lead">
                            Chat Using Socket IO
                        </p>
                    </div>
                </div>
            </div>
            <main style="margin-left: 10%; margin-right:10%;">
                @yield('contentChat')
            </main>
        </div>
    </body>
    @stack('scripts')
    <script type="text/javascript">
        function getCurrentTime() {
            return moment().format('h:m A');
        }

        function getCurrentDateTime() {
            return moment().format('DD/MM/YY h:mm A');
        }

        function dateFormat(datetime) {
            return moment(datetime, 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YY h:mm A');
        }

        function timeFormat(datetime) {
            return moment(datetime, "YYYY-MM-DD HH:mm:ss").format('h:mm A');
        }
    </script>
</html>
