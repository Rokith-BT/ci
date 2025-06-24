<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$userdata = $this->customlib->getUserData();
$logged_in_User = $this->customlib->getLoggedInUserData();
$logged_in_User_Role = json_decode($this->customlib->getStaffRole());
$superadmin_rest = $this->session->userdata['hospitaladmin']['superadmin_restriction'];
?>
<style>
.box-tools .btn-group {
    margin-right: 5px;
}

.search-panel .panel {
    border-radius: 3px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.search-panel .panel-heading {
    font-weight: 600;
    padding: 10px 15px;
    background: #f8f8f8;
}

.search-panel .panel-body {
    padding: 15px;
}

.nav-tabs-custom .nav-tabs>li.active>a {
    border-top-color: #3c8dbc;
    font-weight: 600;
}

.staffcard-container {
    padding: 10px 0;
}

/* Enhanced Staff Card Styles */
.staff-card {
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
    margin-bottom: 15px;
    transition: all 0.2s ease;
    border: 1px solid #e8e8e8;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.staff-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    transform: translateY(-1px);
    border-color: #3c8dbc;
}

.staff-card-body {
    padding: 0;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.staff-profile-image {
    height: 80px;
    overflow: hidden;
    text-align: center;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    position: relative;
}

.staff-profile-image img {
    height: 80px;
    width: 80px;
    object-fit: cover;
    border-radius: 50%;
    margin-top: 0px;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.staff-info {
    padding: 12px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.staff-info h4 {
    margin: 0 0 8px 0;
    font-weight: 600;
    font-size: 14px;
    color: #2c3e50;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.2;
}

.staff-details {
    font-size: 11px;
    flex-grow: 1;
}

.detail-item {
    margin-bottom: 4px;
    color: #666;
    display: flex;
    align-items: center;
    line-height: 1.3;
}

.detail-item i {
    width: 12px;
    margin-right: 6px;
    color: #3c8dbc;
    font-size: 10px;
    flex-shrink: 0;
}

.detail-item span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.role-designation {
    margin-top: 8px;
    display: flex;
    flex-wrap: wrap;
    gap: 3px;
}

.role-badge,
.designation-badge {
    display: inline-block;
    background: #3c8dbc;
    color: #fff;
    padding: 2px 6px;
    border-radius: 12px;
    font-size: 9px;
    font-weight: 500;
    white-space: nowrap;
}

.designation-badge {
    background: #00a65a;
}

.staff-actions {
    padding: 8px 12px;
    background: #fafafa;
    text-align: center;
    border-top: 1px solid #f0f0f0;
    margin-top: auto;
}

.staff-actions .btn {
    margin: 0 2px;
    padding: 4px 8px;
    font-size: 10px;
    border-radius: 4px;
}

.staff-actions .btn-info {
    background-color: #17a2b8;
    border-color: #138496;
}

.staff-actions .btn-primary {
    background-color: #3c8dbc;
    border-color: #337ab7;
}

/* Pagination Styles */
.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
}

.pagination-info {
    color: #666;
    font-size: 13px;
}

.pagination-controls {
    display: flex;
    gap: 5px;
}

.pagination-controls .btn {
    padding: 5px 10px;
    font-size: 12px;
    border-radius: 3px;
}

.pagination-controls .btn-primary {
    background-color: #3c8dbc;
    border-color: #3c8dbc;
}

.pagination-controls .btn-default {
    background-color: #fff;
    border-color: #ddd;
    color: #333;
}

.pagination-controls .btn-default:hover {
    background-color: #f5f5f5;
}

.pagination-controls .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.items-per-page {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #666;
}

.items-per-page select {
    padding: 2px 5px;
    border: 1px solid #ddd;
    border-radius: 3px;
    font-size: 12px;
}

.datatable {
    border-collapse: collapse;
}

.datatable thead th {
    background: #f8f8f8;
    border-bottom: 2px solid #e4e4e4;
}

.datatable tbody tr:hover {
    background-color: #f5f5f5;
}

@media (max-width: 767px) {
    .box-tools {
        float: none !important;
        display: block;
        margin-top: 10px;
        text-align: left;
    }

    .box-tools .btn-group {
        margin-bottom: 5px;
    }

    .search-panel .col-md-6 {
        margin-bottom: 15px;
    }

    .staff-card {
        margin-bottom: 10px;
    }

    .staff-profile-image {
        height: 70px;
    }

    .staff-profile-image img {
        height: 70px;
        width: 70px;
    }

    .pagination-container {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
}


@media (max-width: 576px) {
    .col-lg-3.col-md-4.col-sm-6 {
        -webkit-box-flex: 0;
        -ms-flex: 0 0 50%;
        flex: 0 0 50%;
        max-width: 50%;
    }
}
</style>
<style>
.skeleton {
    background: #f0f0f0;
    border-radius: 8px;
    animation: pulse 1.5s infinite;
}

.skeleton-img {
    width: 100%;
    height: 140px;
    background: #ccc;
    border-radius: 8px;
}

.skeleton-line {
    height: 12px;
    background: #ccc;
    margin-bottom: 10px;
    border-radius: 4px;
}

.w-25 {
    width: 25%;
}

.w-50 {
    width: 50%;
}

.w-75 {
    width: 75%;
}

.w-100 {
    width: 100%;
}

@keyframes pulse {
    0% {
        background-color: #f0f0f0;
    }

    50% {
        background-color: #e0e0e0;
    }

    100% {
        background-color: #f0f0f0;
    }
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('staff_directory'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('staff', 'can_add')) { ?>
                            <div class="btn-group">
                                <a href="<?php echo site_url('admin/staff/create'); ?>"
                                    style="border-radius:2px 0px 0px 2px"
                                    class="btn btn-primary btn-sm btnMDb2"><?php echo $this->lang->line('add_staff'); ?></a>
                                <?php if ($this->rbac->hasPrivilege('disable_staff', 'can_view')) { ?>
                                <button type="button" style="border-left: 1px solid #2e6da4;"
                                    class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?php echo base_url('admin/staff/disablestafflist'); ?>">
                                            <?php echo $this->lang->line('staff_disabled_staff'); ?>
                                        </a>
                                    </li>
                                </ul>
                                <?php } ?>
                            </div>
                            <?php } ?>

                            <?php if ($this->rbac->hasPrivilege('staff_attendance', 'can_view')) { ?>
                            <a href="<?php echo base_url(); ?>admin/staffattendance"
                                class="btn btn-primary btn-sm btnMDb2">
                                <i class="fa fa-reorder"></i> <?php echo $this->lang->line('staff_attendance'); ?>
                            </a>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('staff_payroll', 'can_view')) { ?>
                            <a href="<?php echo base_url(); ?>admin/payroll" class="btn btn-primary btn-sm">
                                <i class="fa fa-reorder"></i> <?php echo $this->lang->line('staff_payroll'); ?>
                            </a>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('apply_leave', 'can_view')) { ?>
                            <a href="<?php echo base_url(); ?>admin/staff/leaverequest" class="btn btn-primary btn-sm">
                                <i class="fa fa-reorder"></i> <?php echo $this->lang->line('staff_leaves'); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?> <div>
                            <?php echo $this->session->flashdata('msg') ?> </div>
                        <?php $this->session->unset_userdata('msg');
                        }   ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/staff') ?>" method="post"
                                        class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line("staff_role"); ?></label><small
                                                    class="req"> *</small>
                                                <select id="role_filter" name="role" class="form-control">
                                                    <option value=""><?php echo $this->lang->line("select"); ?></option>
                                                    <?php foreach ($role as $key => $role_value) { ?>
                                                    <option value="<?php echo $role_value['id'] ?>"
                                                        <?php if ($role_id == $role_value["id"]) echo "selected"; ?>>
                                                        <?php echo $role_value['type'] ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('role'); ?></span>
                                            </div>
                                        </div>

                                        <!-- <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_filter"
                                                    class="btn btn-primary btn-sm pull-right checkbox-toggle"><i
                                                        class="fa fa-search"></i>
                                                    <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div> -->

                                    </form>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/staff') ?>" method="post"
                                        class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_keyword'); ?></label>
                                                <input type="text" name="search_text" class="form-control"
                                                    id="search_text"
                                                    placeholder="<?php echo $this->lang->line('search_by_staff'); ?>">
                                            </div>
                                        </div>
                                        <!-- <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_full"
                                                    class="btn btn-primary pull-right btn-sm checkbox-toggle"><i
                                                        class="fa fa-search"></i>
                                                    <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div> -->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (isset($resultlist)) {
                    ?>
                    <div class="box border0">
                        <div class="box-header ptbnull"></div>
                        <div class="nav-tabs-custom border0">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false"><i
                                            class="fa fa-newspaper-o"></i>
                                        <?php echo $this->lang->line('card_view'); ?></a></li>
                                <li class="">
                                    <a href="#tab_2" data-toggle="tab" aria-expanded="true">
                                        <i class="fa fa-list"></i> <?php echo $this->lang->line('list_view'); ?>
                                    </a>

                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="download_label"><?php echo $title; ?></div>
                                <div class="tab-pane table-responsive no-padding" id="tab_2">
                                    <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                        <div id="table-loader" style="
                                                display: none;
                                                position: absolute;
                                                top: 50%;
                                                left: 50%;
                                                transform: translate(-50%, -50%);
                                                z-index: 10;
                                                background: rgba(255, 255, 255, 0.7);
                                                padding: 10px 20px;
                                                border-radius: 5px;
                                                box-shadow: 0 0 10px rgba(0,0,0,0.2);
                                            ">
                                            <i class="fa fa-spinner fa-spin fa-2x"></i> Loading...
                                        </div>
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th><?php echo $this->lang->line('staff_id'); ?></th>
                                                <th><?php echo $this->lang->line('staff_name'); ?></th>
                                                <th><?php echo $this->lang->line('staff_role'); ?></th>
                                                <th><?php echo $this->lang->line('staff_department'); ?></th>
                                                <th><?php echo $this->lang->line('staff_designation'); ?></th>
                                                <th><?php echo $this->lang->line('staff_mobile_number'); ?></th>
                                                <?php
                                                    if (!empty($fields)) {
                                                        foreach ($fields as $fields_key => $fields_value) {
                                                    ?>
                                                <th><?php echo $fields_value->name; ?></th>
                                                <?php
                                                        }
                                                    }
                                                    ?>
                                                <th class="text-right noExport">
                                                    <?php echo $this->lang->line('action'); ?></th>

                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="tab-pane active" id="tab_1">
                                    <div class="staffcard-container">
                                        <div class="pagination-container">
                                            <div class="pagination-info">
                                                <span id="showing-info-top">Showing 0-0 of 0 staff members</span>
                                            </div>
                                            <div class="items-per-page">
                                                <label>Items per page:</label>
                                                <select id="items-per-page" onchange="changeItemsPerPage()">
                                                    <option value="12" selected>12</option>
                                                    <option value="24">24</option>
                                                    <option value="32">32</option>
                                                    <option value="52">52</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" id="staff-cards-container"></div>

                                        <div class="pagination-container">
                                            <div class="pagination-info">
                                                <span id="showing-info-bottom">Showing 0-0 of 0 staff members</span>
                                            </div>
                                            <div class="pagination-controls">
                                                <button class="btn btn-default btn-sm" onclick="previousPage()" disabled
                                                    id="prev-btn-bottom">
                                                    <i class="fa fa-chevron-left"></i> Previous
                                                </button>
                                                <span id="page-numbers-bottom"></span>
                                                <button class="btn btn-default btn-sm" onclick="nextPage()"
                                                    id="next-btn-bottom">
                                                    Next <i class="fa fa-chevron-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>

    </section>
</div>
<script>
let staffList = [],
    currentPage = 1,
    itemsPerPage = 12,
    totalRecords = 0,
    searchQuery = "",
    debounceTimer;
function fetchStaffData(page = 1) {
    const start = (page - 1) * itemsPerPage;
    const postData = {
        draw: 1,
        start: start,
        length: itemsPerPage,
        search: {
            value: searchQuery
        },
        typeof: "nottable",
        role: $('#role_filter').val(),
    };
    let skeletonHtml = '';
    for (let i = 0; i < itemsPerPage; i++) {
        skeletonHtml +=
            `<div class="col-lg-3 col-md-4 col-sm-6 staff-card-item"><div class="staff-card skeleton"><div class="staff-card-body"><div class="staff-profile-image skeleton-img"></div><div class="staff-info"><div class="skeleton-line w-75 mb-2"></div><div class="skeleton-line w-50 mb-2"></div><div class="skeleton-line w-100 mb-2"></div><div class="skeleton-line w-50 mb-2"></div><div class="skeleton-line w-25"></div></div></div></div></div>`;
    }
    document.getElementById("staff-cards-container").innerHTML = skeletonHtml;
    $.ajax({
        url: "<?= base_url('admin/staff/Getdisablestafffdetials') ?>",
        type: "POST",
        data: postData,
        dataType: "json",
        success: function(response) {
            staffList = response.data || [];
            totalRecords = response.recordsTotal || 0;
            currentPage = page;
            renderStaffCards();
            updatePagination(getTotalPages(totalRecords));
        },
        error: function() {
            alert("Failed to load staff data.");
        }
    });
}
function getImageSrc(staff, imgElement) {
    if (staff.image && staff.image !== "no_image.png") {
        $.ajax({
            url: 'https://phr-api.plenome.com/file_upload/getDocs',
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                value: staff.image
            }),
            success: function(decryptResponse) {
                if (decryptResponse && typeof decryptResponse === 'string' && !decryptResponse.includes(
                        "[object Object]")) {
                    let base64Image = "data:image/png;base64," + decryptResponse;
                    if (imgElement) imgElement.src = base64Image;
                }
            }
        });
    }
    return staff.role_name === "Doctor" ?
        "https://assets.onecompiler.app/42vjmaxtb/43g65qq99/aa7ad15dfb320632ee64552514554631.png" :
        "<?= base_url('uploads/staff_images/no_image.png'); ?>";
}
function renderStaffCards() {
    const container = document.getElementById("staff-cards-container");
    container.innerHTML = "";
    const start = (currentPage - 1) * itemsPerPage;
    const end = Math.min(start + itemsPerPage, totalRecords);
    if (staffList.length === 0) {
        container.innerHTML =
            `<div class="col-md-12"><div class="alert alert-info"><i class="fa fa-info-circle"></i> No record found</div></div>`;
    } else {
        staffList.forEach(staff => {
            const card = document.createElement("div");
            card.className = "col-lg-3 col-md-4 col-sm-6 staff-card-item";
            const imgSrc = getImageSrc(staff);
            card.innerHTML =
                `<div class="staff-card">
                <div class="staff-card-body"><div class="staff-profile-image"><img src="${imgSrc}" alt="${staff.staffname}"></div>
                <div class="staff-info"><h4 title="Name">${staff.staffname}</h4><div class="staff-details"><div class="detail-item"><i class="fa fa-address-card"></i> <span title="Employee Id">${staff.employee_id}</span></div><div class="detail-item"><i class="fa fa-phone"></i> <span title="Contact Number">${staff.contact_no}</span></div><div class="detail-item"><i class="fa fa-map-marker"></i><span title="Location">${staff.local_address ? staff.local_address + ' • ' : ''}<strong>${staff.department_name}</strong></span></div><div class="detail-item role-designation">${staff.role_name ? `<span class="role-badge" title="Role">${staff.role_name}</span>` : ''}${staff.designation ? `<span class="designation-badge" title="Designation">${staff.designation}</span>` : ''}</div></div><div class="staff-actions"><a class="btn btn-info btn-sm" title="View" href="<?= base_url() ?>/admin/staff/profile/${btoa(staff.id)}"><i class="fa fa-eye"></i> View</a></div></div></div></div>`;
            const imgElement = card.querySelector('img');
            getImageSrc(staff, imgElement);
            container.appendChild(card);
        });
    }
    document.getElementById("showing-info-bottom").innerText =
        `Showing ${start + 1}-${end} of ${totalRecords} staff members`;
    document.getElementById("showing-info-top").innerText =
        `Showing ${start + 1}-${end} of ${totalRecords} staff members`;
}

function updatePagination(totalPages) {
    $('#prev-btn, #prev-btn-bottom').prop('disabled', currentPage === 1);
    $('#next-btn, #next-btn-bottom').prop('disabled', currentPage === totalPages || totalPages === 0);
    let pageNumbersHtml = '',
        maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    if (endPage - startPage < maxVisiblePages - 1) startPage = Math.max(1, endPage - maxVisiblePages + 1);
    if (startPage > 1) {
        pageNumbersHtml += `<button class="btn btn-default btn-sm" onclick="goToPage(1)">1</button>`;
        if (startPage > 2) pageNumbersHtml += `<span style="margin: 0 5px;">...</span>`;
    }
    for (let i = startPage; i <= endPage; i++) {
        const activeClass = i === currentPage ? 'btn-primary' : 'btn-default';
        pageNumbersHtml += `<button class="btn ${activeClass} btn-sm" onclick="goToPage(${i})">${i}</button>`;
    }
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) pageNumbersHtml += `<span style="margin: 0 5px;">...</span>`;
        pageNumbersHtml +=
            `<button class="btn btn-default btn-sm" onclick="goToPage(${totalPages})">${totalPages}</button>`;
    }
    $('#page-numbers, #page-numbers-bottom').html(pageNumbersHtml);
    $('#prev-btn, #prev-btn-bottom').off('click').on('click', previousPage);
    $('#next-btn, #next-btn-bottom').off('click').on('click', () => nextPage(totalPages));
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        fetchStaffData(currentPage);
    }
}

function nextPage(totalPages) {
    if (currentPage < totalPages) {
        currentPage++;
        fetchStaffData(currentPage);
    }
}

function goToPage(page) {
    currentPage = page;
    fetchStaffData(currentPage);
}

function getTotalPages(totalRecords) {
    return Math.ceil(totalRecords / itemsPerPage);
}

function changeItemsPerPage() {
    const selectedValue = parseInt(document.getElementById("items-per-page").value);
    if (!isNaN(selectedValue) && selectedValue > 0) {
        itemsPerPage = selectedValue;
        currentPage = 1;
        fetchStaffData(currentPage);
    }
}

$(document).ready(function() {
    $('#search_text').on('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            searchQuery = $('#search_text').val();
            currentPage = 1;
            fetchStaffData(currentPage);
        }, 500);
    });
    fetchStaffData();
});
$('#role_filter').on('change', function() {
    currentPage = 1;
    fetchStaffData(currentPage);
});
</script>
<script>
$(document).ready(function() {
    let Tableid = '#ajaxlist';
    const $tableWrapper = $(Tableid).find('tbody').parent();

    if (!$(Tableid).find('#table-loader').length) {
        $tableWrapper.css('position', 'relative').append(loaderHTMLstyle);
    }

    $(Tableid).find('#table-loader').show();
    const table = $(Tableid).DataTable({
        serverSide: true,
        searching: false,
        ordering: true,
        paging: true,
        lengthMenu: [5, 10, 25, 50],
        pageLength: 10,
        columnDefs: [{
            orderable: false,
            targets: [6]
        }],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<i class="fa fa-files-o"></i>',
                titleAttr: 'Copy',
                className: 'btn btn-default btn-copy'
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',
                className: 'btn btn-default btn-excel'
            },
            {
                extend: 'csvHtml5',
                text: '<i class="fa fa-file-text-o"></i>',
                titleAttr: 'CSV',
                className: 'btn btn-default btn-csv'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'PDF',
                className: 'btn btn-default btn-pdf'
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                titleAttr: 'Print',
                className: 'btn btn-default btn-print'
            }
        ],
        language: {
            emptyTable: "No appointments found"
        },
        ajax: function(data, callback) {
            $(Tableid).find('#table-loader').show();

            const formData = new URLSearchParams();
            formData.append('draw', data.draw);
            formData.append('start', data.start);
            formData.append('length', data.length);
            formData.append('search[value]', $('#search_text').val());
            formData.append('search[regex]', 'false');
            formData.append('role', $('#role_filter').val() || '');

            fetch(`${baseurl}admin/staff/Getdisablestafffdetials`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData.toString()
            })
            .then(res => res.json())
            .then(result => {
                $(Tableid).find('#table-loader').fadeOut();
                renderTable(result.data, result.recordsTotal, data, callback);
            })
            .catch(() => {
                $(Tableid).find('#table-loader').fadeOut();
                callback({
                    draw: data.draw,
                    recordsTotal: 0,
                    recordsFiltered: 0,
                    data: []
                });
            });
        }
    });

    let debounceTimer;
    $('#search_text').on('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            table.ajax.reload();
        }, 300);
    });

    $('#role_filter').on('change', function() {
        table.ajax.reload();
    });
});


function renderTable(dataArray, recordCount, data, callback) {
    let count = data.start || 0;
    const rows = (dataArray || []).map(item => {
        const action = `<td class="pull-right noExport">
            <a href="<?= base_url('admin/staff/profile/') ?>${btoa(item.id)}" class="btn btn-default btn-xs" data-toggle="tooltip" title="Show"><i class="fa fa-reorder"></i></a>
        </td>`;
        return [
            ++count,
            item.employee_id || "-",
            item.staffname || "-",
            item.role_name || "-",
            item.department_name || "-",
            item.designation || "-",
            item.contact_no || "-",
            action
        ];
    });

    callback({
        draw: data.draw,
        recordsTotal: recordCount,
        recordsFiltered: recordCount,
        data: rows
    });

    setTimeout(() => {
        $('[data-toggle="tooltip"]').tooltip();
    }, 100);
}
</script>