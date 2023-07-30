<div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
    <div id="kt_app_sidebar" class="app-sidebar  flex-column " data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
        <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
            <a href="<?php site_url() ?>">
                <img alt="Logo" src="<?php echo get_template_directory_uri() ?> /images/default-dp-eimams.png" class="h-25px app-sidebar-logo-default" />
                <img alt="Logo" src="<?php echo get_template_directory_uri() ?> /images/default-dp-eimams.png" class="h-20px app-sidebar-logo-minimize" />
            </a>
            <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate " data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
                <i class="ki-duotone ki-double-left fs-2 rotate-180"><span class="path1"></span><span class="path2"></span></i>
            </div>
        </div>
        <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
            <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
                <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                    <div data-kt-menu-trigger="click" class="menu-item  menu-accordion">
                        <span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-element-11 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span><span class="menu-title">Dashboard</span></span>
                    </div>
                    <div class="menu-item pt-5">
                        <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Job</span></div>
                    </div>
                    <div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
                        <span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-check-square fs-2"><span class="path1"></span><span class="path2"></span></i></span><span class="menu-title">Applied
                                Job</span></span>
                    </div>
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                        <span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-tablet-text-down
                                                fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span><span class="menu-title">Job
                                Listing</span></span>
                    </div>
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                        <span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-office-bag
                                                fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span><span class="menu-title">Available
                                Jobs</span></span>
                    </div>
                    <div class="menu-item pt-5">
                        <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Subscription</span></div>
                    </div>
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                        <span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-dollar
                                                fs-2"><span class="path1"></span><span class="path2"></span></i></span><span class="menu-title">Subscriptions</span></span>
                    </div>
                    <div class="menu-item pt-5">
                        <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Other</span></div>
                    </div>
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                        <span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-information-4 fs-2"><span class="path1"></span><span class="path2"></span></i></span><span class="menu-title">
                                Help & Support</span></span>
                    </div>
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                        <span class="menu-link"><span class="menu-icon"><i class="ki-duotone ki-question fs-2"><span class="path1"></span><span class="path2"></span></i></span><span class="menu-title">FAQ</span></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
            <a href="https://preview.keenthemes.com/html/metronic/docs" class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click" title="200+ in-house components and 3rd-party plugins">
                <span class="btn-label">
                    Docs & Components
                </span>
                <i class="ki-duotone ki-document btn-icon fs-2 m-0"><span class="path1"></span><span class="path2"></span></i> </a>
        </div>
    </div>