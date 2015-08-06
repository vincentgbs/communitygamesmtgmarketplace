<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title')</title>

        <!-- BOOTSTRAP CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"
            rel="stylesheet" type="text/css">
        <!-- JQUERY JS BASE -->
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <!-- BOOTSTRAP JS -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <!-- JQUERY UI JS -->
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        <link href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"
            rel="stylesheet" type="text/css">

        <!-- GENERAL FUNCTIONS -->
        <script>
        $(document).ready(function () {
            $(".hover-display").hover(
                function(){
                    var id = $(this).attr('id');
                    $(".hover-alert#"+id).show();
                },
                function(){
                    var id = $(this).attr('id');
                    $(".hover-alert#"+id).hide();
                }
            );
        });
        </script>
        <style>
        .hover-alert {
            display: none;
            position:absolute;
            z-index: 1;
        }
        </style>
        <!-- END GENERAL FUNCTION -->

    </head>
    <body>
        @section('sidebar')
            <div class="container col-md-12">
                <div class="col-md-1"><a href="{{ url('/') }}">ColorCreep</a></div>
                <div class="col-md-2">
                    <div class="dropdown">
                        <input type="button" class="btn btn-default dropdown-toggle"
                            id="gen_menu" data-toggle="dropdown" style="width:100%"
                            value="Menu &#9660;"/>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="gen_menu">
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="{{ url('mtg') }}">Magic: the Gathering</a></li>
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="{{ url('mtg/cards') }}">&#9650;Cards</a></li>
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="{{ url('mtg/sets') }}">&#9650;Sets</a></li>
                        <!--
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="#">Pokemon</a></li>
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="#">&#9650;Cards</a></li>
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="#">&#9650;Sets</a></li>
                        -->
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="{{ url('orders') }}">Orders</a></li>
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="{{ url('mail/inbox') }}">Inbox</a></li>
                        <li role="presentation">
                            <a role="menuitem" tabindex="-1" href="{{ url('blogs') }}">Blogs</a></li>
                    </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <form method="POST" action="{{ url('mtg/cards') }}">
                        <span class="glyphicon glyphicon-search"></span>
                        <input type="hidden" name="_token" id="form_token" value="{{ csrf_token() }}"/>
                        <input type="text" name="search" id="search" size="15"/>
                        <button class="btn btn-default btn-sm">Search</button>
                    </form>
                </div>
                @if (Auth::guest())
                    <div class="col-md-2">
                        <a href="{{ url('/auth/login') }}"><button class="btn btn-default"
                            style="width:100%">Login</button></a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ url('/auth/register') }}"><button class="btn btn-default"
                            style="width:100%">Register</button></a>
                    </div>
                @else
                    <div class="col-md-2">
                        <a href="{{ url('/sell') }}"><button class="btn btn-default"
                            style="width:100%">Sell</button></a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ url('/auth/logout') }}"><button class="btn btn-default"
                            style="width:100%">Logout</button></a>
                    </div>
                @endif
                <div class="col-md-2">
                    <div class="dropdown">
                        <input type="button" class="btn btn-default dropdown-toggle"
                            id="shopping_cart" data-toggle="dropdown" style="width:100%"
                            value="Cart &#9660;"/>
                    <ul class="dropdown-menu" id="cart" role="menu" aria-labelledby="shopping_cart">
                        <button class="btn col-md-12" id="cart_refresh"><span class="glyphicon glyphicon-refresh"></span></button>
                        <a href="{{ url('cart') }}">
                            <div class="col-md-12" id="cart_preview">
                                @include('buy/cartPreview')
                            </div>
                        </a>
                    </ul>
                    </div>

                    <style>
                    #cart {
                        left: -100%;
                        width: 200%;
                        height: 700%;
                    }
                    .cart {
                        font-size: 1rem;
                    }
                    </style>

                    <script>
                        function updateCartAjax() {
                                return $.ajax({
                                    url: "/update_cart_preview",
                                    type: "POST",
                                    dataType: "json",
                                    data: {
                                        _token: $("#form_token").val(),
                                    },
                                    async: false,
                                    statusCode: {
                                        401: function() { alert("Authentication Error"); },
                                        500: function() { alert("Server Error, please try again later."); }
                                    },
                                    success: function(data) {
                                        return data;
                                    }
                                });
                            }

                            $("#cart_refresh").click(function(){
                                var cart = updateCartAjax().responseText;
                                // console.log(cart);
                                $(".col-md-12#cart_preview").prepend().html(cart);
                            });
                    </script>
                </div>
            </div>
        @show

        <style>
            #content {
                text-align: center;
            }
        </style>
        <div class="container col-md-12" id="content">
            @if (Session::has('alert'))
                <div class="alert alert-danger">
                    {{ Session::get('alert') }}
                </div>
            @endif

            @yield('content')
        </div>

        @section('footer')
            <div class="container col-md-offset-1 col-md-11">
                <div class="col-md-2">
                    <a href="{{ url('user_agreement') }}"><button class="btn btn-default" style="width:100%">
                        User Agreement</button></a>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-default" style="width:100%">Privacy Policies</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ url('contact_us') }}"><button class="btn btn-default" style="width:100%">
                        Contact Us</button></a>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-default" style="width:100%">Careers</button>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-default" style="width:100%">Announcements</button>
                </div>
            </div>
            <div class="row" style="width:100%;text-align:center">
                <small>Sources:
                <a href="http://glyphicons.com"> Glyphicons </a>|
                <!-- <a href="http://getbootstrap.com"> --> Bootstrap <!--</a>-->|
                <!-- <a href="https://jquery.com"> --> jQuery <!--</a>-->|
                <!-- <a href="https://jqueryui.com"> --> jQuery Ui <!--</a>-->|
                <!-- <a href="https://jqueryvalidation.org"> --> jQuery Validation <!--</a>-->|
                <!-- <a href="http://jquerypriceformat.com"> --> jQuery Price <!--</a>-->
                </small>
            </div>
        @show
    </body>
</html>
