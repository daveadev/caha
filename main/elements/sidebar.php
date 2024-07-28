<div class="sidebar-blind ease">
    <div class="btn-group btn-sm" ng-if="__SIDEBAR_OPEN"> 
        <button class="btn btn-reverse">Today</button>
        <button class="btn btn-reverse">Notifications</button>
    </div>
</div>
<!-- Sidebar -->
<div id="sidebar-wrapper" class="ease">
    <div class="navbar navbar-default" id="offcanvas-navbar-toggle">
        <button type="button" class="navbar-toggle c-hamburger c-hamburger--htx" ng-class="{'is-active':__SIDEBAR_OPEN}" id="menu-toggle-1" ng-click="__toggleSideBar()" >
           <span>Toggle Menu</span>
        </button>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <div id="user-name"> {{__USER.user.username}}</div>
            <div id="company-name">{{_APP.SCHOOL_NAME}}</div>
        </li>
        <li class="separator">
        </li>
        <li>
            <a href="#/" ng-click="__toggleSideBar()">Home</a>
        </li>
        <li ng-repeat="Menu in __SIDEBAR_MENUS">
            <a ng-if="!Menu.is_parent" href="#/{{Menu.link}}" ng-click="__toggleSideBar()">
                {{ Menu.name}}
                
            </a>
            <a ng-if="Menu.is_parent && Menu.is_granted" class="accordion-toggle collapsed toggle-switch" data-toggle="collapse" ng-class="{active:ActiveMenu==Menu.id}" ng-click="__toggleSubMenu(Menu)">
                <b class="caret"></b>
                <span class="sidebar-title">{{Menu.name}}</span>
                
            </a>
            <ul ng-if="Menu.is_parent && Menu.is_granted" ng-class="{in:ActiveMenu==Menu.id}" class="panel-collapse collapse panel-switch" role="menu">
                <li class="separator"></li>
                <li ng-repeat="Submenu in Menu.children">
                    <a href="#/{{Submenu.link}}" ng-click="__loadModule(Submenu)">{{Submenu.name}}</a>
                </li>
                <li class="separator"></li>
            </ul>
        </li>
        <li>
            <a href="#/logout">Logout</a>
        </li>
    </ul>
    <div id="app-version"><?php echo "$alias $versionNo";?></div>
</div>