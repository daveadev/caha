<!-- Top bar-->
<div class="navbar navbar-default navbar-fixed-top" id="navbar-toggle" ng-show="__LOGGEDIN" ng-class="{'blur': __MODAL_OPEN}">
    <div class="container-fluid">
        <div class="navbar-header" id="main-top-bar">
            <button type="button" class="navbar-toggle c-hamburger c-hamburger--htx fade out in" ng-class="{'in':__APP_READY}" id="menu-toggle-1" ng-click="__toggleSideBar()">
                <span>Toggle Menu</span>
            </button>
            
            <a class="navbar-brand fade out ng-binding in" ng-show="__LOGGEDIN" ng-class="{'in':__FAB_READY}"> </a>
            <!-- Moved the profile dropdown here to be in-line with the hamburger menu -->
            <ul class="nav navbar-nav navbar-right" id="profile-dropdown">
                <li class="dropdown" ng-class="{open:toggleDD}">
                    <a class="dropdown-toggle ng-binding" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" ng-click="toggleDD=!toggleDD">
                        {{__USER.user.username}}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu open">
                        <li><a href="#/account">
                            <span class="glyphicon glyphicon-user"></span>
                            Account Info</a></li>
                        <li><a href="#/">
                            <span class="glyphicon glyphicon-home"></span>
                            Home</a></li>
                        <li role="separator" class="divider"> </li>
                        
                        <li><a href="#/logout">
                            <span class="glyphicon glyphicon-off"></span>
                            Log out</a></li>
                        <li role="separator" class="divider"> </li>
                        
                        <li class="dropdown-header">SRP VERSION </li>
                        <li> <a href="#/?latest=<?php echo $versionNo;?>" ng-click="__showVersionDialog($event,'<?php echo $versionNo;?>')"><?php echo $versionNo;?></a></li>
                    </ul>
                </li>
            </ul>
    
        </div>
    
        <div class="loader fade ib" ng-class="{'out':__APP_READY,'in':!__APP_READY}"></div>
    </div>
</div>