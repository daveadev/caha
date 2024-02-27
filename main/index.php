<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link href="../app/assets/css/bootstrap.min.css" rel="stylesheet">
        <!-- Request policy.json to update policy.js-->
        <script src="../api/policy.json"></script>
        <script data-main="../config/main.js" src="../app/bower_components/requirejs/require.js"> </script>
        <style>
            #academix li.list-group-item button.btn{border:none;margin-top:0.5rem;}
        </style>
    </head>
    <body ng-controller="RootController" class="" ng-init="initRoot()">
        <div class="container-fluid" style="margin:0 2.5rem;margin-top:2.5rem">
            <div class="row">
                <!-- Sidebar -->
                <aside class="col-md-2">
                    <!-- Sidebar content goes here -->
                    <!-- Example: -->
                    <nav>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                               <h5 class="pull-left">Welcome, Juan</h5>
                               <div class="btn-group btn-group-sm pull-right">
                                    <button class="btn btn-default">
                                        <span class="glyphicon glyphicon-chevron-left"></span>
                                    </button>
                                </div>
                               <div class="clearfix"></div>
                            </div>
                            <ul class="list-group">
                                <li class="list-group-item">Home</li>
                                <li class="list-group-item">Search</li>
                            </ul>
                        </div>
                    </nav>

                    <nav id="academix">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="pull-left">
                                    <div>SY 2023 - 2024</div>
                                    <small>First Grading</small>
                                </h4>
                                <button class="btn btn-default pull-right">
                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                </button>
                                <div class="clearfix"></div>
                            </div>

                            <ul class="list-group" style="height:70vh;">
                                <li class="list-group-item">
                                    <h5 class="pull-left">Adviser</h5>
                                    <div class="btn-group btn-group-xs pull-right"> 
                                        <button class="btn btn-default">
                                            <span class="glyphicon glyphicon-th-list"></span>
                                        </button>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="row text-center" style="margin-top:1rem">
                                        <div class="col-md-4">
                                            <a class="btn btn-default btn-block">
                                                <span class="glyphicon glyphicon-folder-open"></span>
                                            </a> 
                                            <small>Conso</small> 
                                        </div>
                                        <div class="col-md-4">
                                            <a class="btn btn-default btn-block">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </a> 
                                            <small>Attendance</small> 
                                        </div>
                                        <div class="col-md-4">
                                            <a class="btn btn-default btn-block">
                                                <span class="glyphicon glyphicon-heart"></span>
                                            </a> 
                                            <small>Deportment</small> 
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <h5 class="pull-left">Subjects</h5>
                                    <div class="btn-group btn-group-xs pull-right">
                                        <button class="btn btn-default">
                                            <span class="glyphicon glyphicon-filter"></span>
                                        </button>
                                        <button class="btn btn-default">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </div>
                                    <div class="clearfix"></div>
                                </li>
                                <li class="list-group-item">
                                    <h4 class="list-group-item-heading">English</h4>
                                    <p class="list-group-item-text">G8 Sampaguita</p>
                                </li>
                                <li class="list-group-item">
                                    <h4 class="list-group-item-heading">English</h4>
                                    <p class="list-group-item-text">G8 Rosal</p>
                                </li>
                                <li class="list-group-item">
                                    <h4 class="list-group-item-heading">Music</h4>
                                    <p class="list-group-item-text">G8 Rosal</p>
                                </li>
                                <li class="list-group-item">
                                    <h4 class="list-group-item-heading">Arts</h4>
                                    <p class="list-group-item-text">G8 Rosal</p>
                                </li>
                                <li class="list-group-item">
                                    <h4 class="list-group-item-heading">P.E.</h4>
                                    <p class="list-group-item-text">G8 Rosal</p>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </aside>

                <!-- Main Content -->
                <main class="col-md-10">
                    <div class="panel panel-default" style="height:95vh">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="text" class="form-control input-lg pull-right" placeholder="Search students, sections or subjects.">
                                </div>
                                <div class="col-md-2 text-right">
                                    <div class="btn-group btn-group-lg">
                                        <button class="btn btn-default">
                                            <span class="glyphicon glyphicon-inbox"></span>
                                        </button>
                                        <button class="btn btn-default">
                                            <span class="glyphicon glyphicon-bookmark"></span>
                                        </button>
                                        <button class="btn btn-default">
                                            <span class="glyphicon glyphicon-user"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>