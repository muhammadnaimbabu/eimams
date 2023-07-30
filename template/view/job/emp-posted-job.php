<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar  py-3 py-lg-6 ">

    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="container-xxl d-flex flex-stack ">

        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                Posted Jobs
            </h1>
            <!--end::Title-->

            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <!--begin::Item-->
                <span>All your posted job be will display here.</span>
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
        <!--begin::Actions-->

        <!--end::Actions-->
    </div>
    <!--end::Toolbar container-->
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content  flex-column-fluid ">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="container-xxl ">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card body-->
            <div class="card-body pt-0">

                <!--begin::Table-->
                <div id="az_posted_jobs" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer" id="kt_customers_table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1" colspan="1" style="width: 170.984px;">ID</th>
                                    <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1" colspan="1" style="width: 211.578px;">Reference</th>
                                    <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1" colspan="1" style="width: 187.938px;">Job Title</th>
                                    <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1" colspan="1" style="width: 187.938px;">Start Date</th>
                                    <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1" colspan="1" style="width: 187.938px;">End Date</th>
                                    <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1" colspan="1" style="width: 187.938px;">Status</th>
                                    <th class="text-end min-w-70px sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" style="width: 140.062px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <?php
                                $query = new WP_Query($args_current_user);
                                while ($query->have_posts()) :
                                    $query->the_post();
                                    $status = get_post_status(get_the_ID());
                                    echo '<tr class="odd gradeX color_change" >';
                                    echo "<td class='row-id'>" . get_the_ID() . "</td>";
                                    echo "<td>" . get_post_meta(get_the_ID(), 'employer_ref', true) . "</td>";
                                    echo "<td> <a href='" . site_url('job-view') . "?job_id=" . get_the_ID() . "' >" . get_the_title() . "</a></td>";
                                    echo "<td>" . get_post_meta(get_the_ID(), 'ad_start_date', true) . "</td>";
                                    echo "<td>" . get_post_meta(get_the_ID(), 'ad_close_date', true) . "</td>";

                                    if ($status == 'expired') {
                                        echo "<td class='text-danger'>" . get_post_status(get_the_ID()) . "</td>";
                                    } elseif ($status == 'pending') {
                                        echo "<td class='text-info'>" . get_post_status(get_the_ID()) . "</td>";
                                    } else {
                                        echo "<td>" . get_post_status(get_the_ID()) . "</td>";
                                    }
                                ?>
                                    <td class="text-end">

                                        <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Actions
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="<?php echo site_url('edit-job/?edit_id=') . get_the_ID() ?>" class="menu-link px-3">
                                                    Edit
                                                </a>
                                            </div>
                                            <!--end::Menu item-->

                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a data-id='<?php echo get_the_ID() ?>' class="menu-link px-3 delete-row">
                                                    Delete
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                    </tr>
                                <?php endwhile ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <!--end::Table-->
            </div>
        </div>
    </div>
    <!--end::Content container-->
</div>


<script>
    // Function on click
    jQuery(".delete-row").click(function() {
        event.preventDefault();
        var id = jQuery(this).data('id');
        var link = "<?php echo admin_url('admin-ajax.php') ?>";
        jQuery.ajax({
            type: "POST",
            url: link,
            data: {
                action: "delete_job",
                del_id: id
            },
            success: function(data) {
                alert(data);
                location.reload();
            },
            error: function(errorThrown) {
                console.log(errorThrown);
            }
        });
    });

    $(document).ready(function() {
        $('#kt_customers_table').DataTable({
            dom: "Bfrtip",
            buttons: [{
                    extend: 'copy',
                    className: 'btn btn-success  mt-10'
                },
                {
                    extend: 'excel',
                    className: 'excelButton  mt-10'
                },
                {
                    extend: 'print',
                    className: 'btn btn-info mt-10'
                }
            ]
        });
    });
</script>