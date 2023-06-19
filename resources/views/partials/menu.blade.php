<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            {{ trans('panel.site_title') }}
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li>
            <select class="searchable-field form-control">

            </select>
        </li>

        @can('feetManager')
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.fleet.charts") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        @endcan
        @can('role_access')
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.charts") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        @endcan
        @canany(['user_access', 'role_access','feetManager'])
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/permissions*") ? "c-show" : "" }} {{ request()->is("admin/roles*") ? "c-show" : "" }} {{ request()->is("admin/users*") ? "c-show" : "" }} {{ request()->is("admin/cars*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/users") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.fields.banzenty_admins') }}
                            </a>
                        </li>
                    @endcan
                    @canany(['user_access'])
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.car.owners") }}" class="c-sidebar-nav-link {{ request()->is("admin/users")  ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.fields.car_owners') }}
                            </a>
                        </li>
                    @endcan
                    @canany(['user_access'])
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.fleets") }}" class="c-sidebar-nav-link {{ request()->is("admin/users/fleets") || request()->is("admin/users/fleets/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.fields.fleetmanager') }}
                            </a>
                        </li>
                    @endcan
                    @canany(['feetManager'])
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.fleet.users") }}" class="c-sidebar-nav-link {{ request()->is("admin/users")  ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.fields.my_cars') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.station.admins") }}" class="c-sidebar-nav-link {{ request()->is("admin/users")  ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.fields.station_admins') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.employees") }}" class="c-sidebar-nav-link {{ request()->is("admin/users") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.fields.employees') }}
                            </a>
                        </li>
                    @endcan
                    @can('car_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.cars.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/cars") || request()->is("admin/cars/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-car c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.car.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        @canany(['station_access', 'limited_station_access'])
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.stations.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/stations") || request()->is("admin/stations/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-gas-pump c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.station.title') }}
                </a>
            </li>
        @endcanany
        @can('company_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.companies.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/companies") || request()->is("admin/companies/*") ? "c-active" : "" }}">
                    <i class="fa-fw far fa-registered c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.company.title') }}
                </a>
            </li>
        @endcan
        @can('fuel_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.fuels.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/fuels") || request()->is("admin/fuels/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-flask c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.fuel.title') }}
                </a>
            </li>
        @endcan
        @can('service_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.services.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/services") || request()->is("admin/services/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-users-cog c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.service.title') }}
                </a>
            </li>
        @endcan
        @can('category_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.categories.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/categories") || request()->is("admin/categories/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-th c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.category.title') }}
                </a>
            </li>
        @endcan
        @canany(['request_access', 'limited_request_access'])
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.orders.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/orders") || request()->is("admin/orders/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-receipt c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.order.title') }}
                </a>
            </li>
        @endcanany
        @canany(['feetManager'])
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.orders.my-orders") }}" class="c-sidebar-nav-link {{ request()->is("admin/orders") || request()->is("admin/orders/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-receipt c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.order.title') }}
                </a>
            </li>
        @endcanany
        @can('plan_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.plans.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/plans") || request()->is("admin/plans/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-shopping-cart c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.plan.title') }}
                </a>
            </li>
        @endcan
        @can('subscription_request_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.subscription-requests.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/subscription-requests") || request()->is("admin/subscription-requests/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-shopping-cart c-sidebar-nav-icon"></i>
                    {{ trans('cruds.subscription-request.title') }}
                </a>
            </li>
        @endcan
        @can('setting_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/contct-us*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fas fa-envelope-open c-sidebar-nav-icon"></i>
                    {{ trans('cruds.contact_us.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.contact.us.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/contct-us") || request()->is("admin/contct-us/*") ? "c-active" : "" }}">
                            <i class="fas fa-inbox c-sidebar-nav-icon"></i>
                            {{ trans('cruds.contact_us.title_menu') }}
                        </a>
                    </li>
                </ul>
            </li>
            <li class="c-sidebar-nav-item">

            </li>
        @endcan
        @can('reward_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.rewards.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/rewards") || request()->is("admin/rewards/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-gift c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.reward.title') }}
                </a>
            </li>
        @endcan
        @can('banner_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.banners.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/banners") || request()->is("admin/banners/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-digital-tachograph c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.banner.title') }}
                </a>
            </li>
        @endcan
        @can('notification_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.notifications.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/notifications") || request()->is("admin/notifications/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-bell c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.notification.title') }}
                </a>
            </li>
        @endcan
        @can('setting_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/settings*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon"></i>
                    {{ trans('cruds.settings.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.settings.edit") }}" class="c-sidebar-nav-link {{ request()->is("admin/settings") || request()->is("admin/settings/*") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-cogs c-sidebar-nav-icon"></i>
                            {{ trans('cruds.settings.general') }}
                        </a>
                    </li>
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.settings.terms.edit") }}" class="c-sidebar-nav-link {{ request()->is("admin/settings/terms") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-cogs c-sidebar-nav-icon"></i>
                            {{ trans('cruds.settings.terms_and_conditions') }}
                        </a>
                    </li>
                    <li class="c-sidebar-nav-item">
                        <a href="{{ route("admin.settings.privacy.edit") }}" class="c-sidebar-nav-link {{ request()->is("admin/settings/privacy") ? "c-active" : "" }}">
                            <i class="fa-fw fas fa-cogs c-sidebar-nav-icon"></i>
                            {{ trans('cruds.settings.privacy_policy') }}
                        </a>
                    </li>
                </ul>
            </li>
            <li class="c-sidebar-nav-item">

            </li>
        @endcan
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                        <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                        </i>
                        {{ trans('global.change_password') }}
                    </a>
                </li>
            @endcan
        @endif
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>
