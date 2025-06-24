<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php $this->load->view('setting/sidebar'); ?>
            <div class="col-md-10">
                <div class="nav-tabs-custom">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('sms_setting'); ?></h3>
                    </div>
                    <ul class="nav nav-tabs navlistscroll">
                        <li class="active"><a href="#tab_1" data-toggle="tab"><?php echo $this->lang->line('clickatell_sms_gateway'); ?></a></li>
                        <li><a href="#tab_2" data-toggle="tab"><?php echo $this->lang->line('twilio_sms_gateway'); ?></a></li>
                        <li><a href="#tab_4" data-toggle="tab"><?php echo $this->lang->line('msg_91'); ?></a></li>
                        <li><a href="#tab_6" data-toggle="tab"><?php echo $this->lang->line('text_local'); ?></a></li>
                        <li><a href="#tab_5" data-toggle="tab"><?php echo $this->lang->line('sms_country'); ?></a></li>
                        <li><a href="#tab_7" data-toggle="tab"><?php echo $this->lang->line('bulk_sms'); ?></a></li>
                        <li><a href="#tab_8" data-toggle="tab"><?php echo $this->lang->line('mobireach'); ?></a></li>
                        <li><a href="#tab_9" data-toggle="tab"><?php echo $this->lang->line('nexmo'); ?></a></li>
                        <li><a href="#tab_10" data-toggle="tab"><?php echo $this->lang->line('africastalking'); ?></a></li>
                        <li><a href="#tab_3" data-toggle="tab"><?php echo $this->lang->line('custom_sms_gateway'); ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <form role="form" id="clickatell" action="<?php echo site_url('smsconfig/clickatell') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12 minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $clickatell_result = check_in_array('clickatell', $smslist);
                                                ?>

                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('clickatell_username'); ?> <small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input autofocus="" type="text" class="form-control" name="clickatell_user" value="<?php echo $clickatell_result->username; ?>">
                                                        <span class=" text text-danger clickatell_user_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('clickatell_password'); ?> <small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="password" class="form-control" name="clickatell_password" value="<?php echo $clickatell_result->password; ?>">
                                                        <span class=" text text-danger clickatell_password_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('clickatell_api_key'); ?> <small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="clickatell_api_id" value="<?php echo $clickatell_result->api_id; ?>">
                                                        <span class=" text text-danger clickatell_api_id_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?></label>
                                                    <div class="col-sm-7">

                                                        <select class="form-control" name="clickatell_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                            ?>
                                                                <option
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($clickatell_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>><?php echo $s_value; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class=" text text-danger clickatell_api_id_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.clickatell.com/" target="_blank"><img src="<?php echo base_url() ?>backend/images/clickatell.png">
                                                    <p>https://www.clickatell.com</p>
                                                </a>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <!-- /.box-body -->
                                <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) { ?>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary col-md-offset-3"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="clickatell_loader"></span>
                                    </div>
                                <?php } ?>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_2">
                            <form role="form" id="twilio" id="twilio" action="<?php echo site_url('smsconfig/twilio') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12 minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $twilio_result = check_in_array('twilio', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('twilio_account_sid'); ?> <small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="twilio_account_sid" value="<?php echo $twilio_result->api_id; ?>">
                                                        <span class="text text-danger twilio_account_sid_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('authentication_token'); ?> <small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="twilio_auth_token" value="<?php echo $twilio_result->password; ?>">
                                                        <span class="text text-danger twilio_auth_token_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('registered_phone_number'); ?> <small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="twilio_sender_phone_number" value="<?php echo $twilio_result->contact; ?>">
                                                        <span class="text text-danger twilio_sender_phone_number_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="twilio_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                            ?>
                                                                <option
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($twilio_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>><?php echo $s_value; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class=" text text-danger clickatell_api_id_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.twilio.com/?v=t" target="_blank"><img src="<?php echo base_url() ?>backend/images/twilio.png">
                                                    <p>https://www.twilio.com</p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) { ?>
                                        <button type="submit" class="btn btn-primary col-md-offset-3"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="twilio_loader"></span>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_3">
                            <form role="form" id="custom" id="custom" action="<?php echo site_url('smsconfig/custom') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12 minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $custom_result = check_in_array('custom', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('gateway_name'); ?><small class="req"> *</small>
                                                    </label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="name" value="<?php echo $custom_result->name; ?>">
                                                        <span class="text text-danger name_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="custom_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                            ?>
                                                                <option
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($custom_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>><?php echo $s_value; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class=" text text-danger clickatell_api_id_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href=""><img src="<?php echo base_url() ?>backend/images/custom-sms.png"></a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) { ?>
                                        <button type="submit" class="btn btn-primary col-md-offset-3"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="custom_loader"></span>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_4">
                            <form role="form" id="msg_nineone" id="msg_nineone" action="<?php echo site_url('smsconfig/msgnineone') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12 minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $msg_nineone_result = check_in_array('msg_nineone', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('auth_Key'); ?> <small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="authkey" value="<?php echo $msg_nineone_result->authkey; ?>">
                                                        <span class="text text-danger authkey_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('sender_id'); ?> <small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="senderid" value="<?php echo $msg_nineone_result->senderid; ?>">
                                                        <span class="text text-danger senderid_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="msg_nineone_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                            ?>
                                                                <option
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($msg_nineone_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>><?php echo $s_value; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class=" text text-danger clickatell_api_id_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://msg91.com/" target="_blank"><img src="<?php echo base_url() ?>backend/images/msg91.png">
                                                    <p>https://msg91.com</p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) { ?>
                                        <button type="submit" class="btn btn-primary col-md-offset-3"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="msg_nineone_loader"></span>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_5">
                            <form role="form" id="smscountry" id="smscountry" action="<?php echo site_url('smsconfig/smscountry') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12 minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $smscountry_result = check_in_array('smscountry', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('username'); ?> <small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="smscountry" value="<?php echo $smscountry_result->username; ?>">
                                                        <span class="text text-danger smscountry_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('sender_id'); ?> <small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="smscountrysenderid" value="<?php echo $smscountry_result->senderid; ?>">
                                                        <span class="text text-danger smscountrysenderid_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('password'); ?> <small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="password" class="form-control" name="smscountrypassword" value="<?php echo $smscountry_result->password; ?>">
                                                        <span class="text text-danger smscountrypassword_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="smscountry_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                            ?>
                                                                <option
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($smscountry_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>><?php echo $s_value; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class=" text text-danger clickatell_api_id_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5  text text-center disblock">
                                                <a href="https://www.smscountry.com/" target="_blank"><img src="<?php echo base_url() ?>backend/images/sms-country.jpg">
                                                    <p>https://www.smscountry.com</p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) { ?>
                                        <button type="submit" class="btn btn-primary col-md-offset-3"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="smscountry_loader"></span>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_6">

                            <form role="form" id="text_local" id="text_local" action="<?php echo site_url('smsconfig/textlocal') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12 minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $text_local_result = check_in_array('text_local', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('username'); ?> <small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="text_local" value="<?php echo $text_local_result->username; ?>">
                                                        <span class="text text-danger text_local_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('hash_key'); ?> <small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="password" class="form-control" name="text_localpassword" value="<?php echo $text_local_result->password; ?>">
                                                        <span class="text text-danger text_localpassword_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('sender_id'); ?> <small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="text_localsenderid" value="<?php echo $text_local_result->senderid; ?>">
                                                        <span class="text text-danger text_localsenderid_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="text_local_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                            ?>
                                                                <option
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($text_local_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>><?php echo $s_value; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class=" text text-danger clickatell_api_id_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.textlocal.in/" target="_blank"><img src="<?php echo base_url() ?>backend/images/textlocal.png">
                                                    <p>https://www.textlocal.in</p>
                                                </a>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) { ?>
                                        <button type="submit" class="btn btn-primary col-md-offset-3"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="text_local_loader"></span>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane" id="tab_7">

                            <form role="form" id="bulk_sms" action="<?php echo site_url('smsconfig/bulk_sms') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12 minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $bulk_sms_result = check_in_array('bulk_sms', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('username'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="bulk_sms_user_name" value="<?php echo $bulk_sms_result->username; ?>">
                                                        <span class="text text-danger bulk_sms_user_name_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('password'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="password" class="form-control" name="bulk_sms_user_password" value="<?php echo $bulk_sms_result->password; ?>">
                                                        <span class="text text-danger bulk_sms_user_password_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="bulk_sms_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                            ?>
                                                                <option
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($bulk_sms_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>><?php echo $s_value; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class=" text text-danger bulk_sms_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://www.textlocal.in/" target="_blank"><img src="<?php echo base_url() ?>backend/images/bulk_sms.png" class="img-responsive center-block">
                                                    <p>https://www.textlocal.in</p>
                                                </a>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                    ?>
                                        <button type="submit" class="btn btn-primary col-md-offset-3"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="bulk_sms_loader"></span>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane" id="tab_8">

                            <form role="form" id="mobireach" action="<?php echo site_url('smsconfig/mobireach') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12 minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $mobireach_result = check_in_array('mobireach', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('auth_Key'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="mobireach_auth_key" value="<?php echo $mobireach_result->authkey; ?>">
                                                        <span class="text text-danger mobireach_auth_key_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('sender_id'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="mobireach_sender_id" value="<?php echo $mobireach_result->senderid; ?>">
                                                        <span class="text text-danger mobireach_sender_id_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('route_id'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="mobireach_route_id" value="<?php echo $mobireach_result->api_id; ?>">
                                                        <span class="text text-danger mobireach_route_id_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="mobireach_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                            ?>
                                                                <option
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($mobireach_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>><?php echo $s_value; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class=" text text-danger mobireach_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://user.mobireach.com.bd/" target="_blank"><img src="<?php echo base_url() ?>backend/images/mobireach.jpg">
                                                    <p>https://user.mobireach.com.bd/</p>
                                                </a>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                    ?>
                                        <button type="submit" class="btn btn-primary col-md-offset-3"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="mobireach_loader"></span>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane" id="tab_9">

                            <form role="form" id="nexmo" action="<?php echo site_url('smsconfig/nexmo') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12 minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $nexmo_result = check_in_array('nexmo', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('api_key'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="nexmo_api_key" value="<?php echo $nexmo_result->api_id; ?>">
                                                        <span class="text text-danger nexmo_api_key_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo  $this->lang->line('nexmo_api_secret'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="nexmo_api_secret" value="<?php echo $nexmo_result->authkey; ?>">
                                                        <span class="text text-danger nexmo_api_secret_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo  $this->lang->line('registered_from_number'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="nexmo_registered_phone_number" value="<?php echo $nexmo_result->senderid; ?>">
                                                        <span class="text text-danger nexmo_registered_phone_number_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="nexmo_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                            ?>
                                                                <option
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($nexmo_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>><?php echo $s_value; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class=" text text-danger nexmo_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://dashboard.nexmo.com/sign-up" target="_blank"><img src="<?php echo base_url() ?>backend/images/nexmo.jpg">
                                                    <p>https://dashboard.nexmo.com/sign-up</p>
                                                </a>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                    ?>
                                        <button type="submit" class="btn btn-primary col-md-offset-3"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="nexmo_loader"></span>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>


                        <div class="tab-pane" id="tab_10">

                            <form role="form" id="africastalking" action="<?php echo site_url('smsconfig/africastalking') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12 minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $africastalking_result = check_in_array('africastalking', $smslist);
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('username'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="africastalking_username" value="<?php echo $africastalking_result->username; ?>">
                                                        <span class="text text-danger africastalking_username_error"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('api_key'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="africastalking_apikey" value="<?php echo $africastalking_result->api_id; ?>">
                                                        <span class="text text-danger africastalking_apikey_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('short_code'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="africastalking_short_code" value="<?php echo $africastalking_result->senderid; ?>">
                                                        <span class="text text-danger africastalking_short_code_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="africastalking_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                            ?>
                                                                <option
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($africastalking_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>><?php echo $s_value; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class=" text text-danger africastalking_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <a href="https://africastalking.com/" target="_blank"><img src="<?php echo base_url() ?>backend/images/africastalking.png">
                                                    <p>https://africastalking.com/</p>
                                                </a>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                    ?>
                                        <button type="submit" class="btn btn-primary col-md-offset-3"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="nexmo_loader"></span>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
        </div>
    </section>
</div>

<?php

function check_in_array($find, $array)
{

    foreach ($array as $element) {
        if ($find == $element->type) {
            return $element;
        }
    }
    $object = new stdClass();
    $object->id = "";
    $object->type = "";
    $object->api_id = "";
    $object->username = "";
    $object->url = "";
    $object->name = "";
    $object->contact = "";
    $object->password = "";
    $object->authkey = "";
    $object->senderid = "";
    $object->is_active = "";
    return $object;
}
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script type="text/javascript">
    var img_path = "<?php echo base_url() . '/backend/images/loading.gif' ?>";
    const url = '<?= $api_base_url ?>settings-sms-settings';

    function handleFormSubmission(formId, loaderClass, buildDataCallback) {
        $(formId).submit(function (e) {
            e.preventDefault();
            $("[class$='_error']").html("");
            $(loaderClass).html('<img src="' + img_path + '">');

            let formData = $(formId).serializeArray();
            let jsonData = {};
            $.each(formData, function (i, field) {
                jsonData[field.name] = field.value;
            });

            const finaldata = buildDataCallback(jsonData);

            $.ajax({
                type: "POST",
                dataType: 'JSON',
                url: url,
                data: finaldata,
                success: function (data, textStatus, jqXHR) {
                    let message = data?.[0]?.["data "]?.message || "Success";
                    successMsg(message);
                    $(loaderClass).html("");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $(loaderClass).html("");
                    console.error("Error: " + errorThrown);
                }
            });
        });
    }

    handleFormSubmission("#clickatell", ".clickatell_loader", function (jsonData) {
        return {
            "type": "clickatell",
            "name": " ",
            "api_id": jsonData.clickatell_api_id,
            "authkey": "",
            "senderid": "hhhhu",
            "contact": "",
            "username": jsonData.clickatell_user,
            "url": "",
            "password": jsonData.clickatell_password,
            "is_active": jsonData.clickatell_status
        };
    });

    handleFormSubmission("#twilio", ".twilio_loader", function (jsonData) {
        return {
            "type": "twilio",
            "name": " ",
            "api_id": jsonData.twilio_account_sid,
            "authkey": "",
            "senderid": "hhhhu",
            "contact": jsonData.twilio_sender_phone_number,
            "username": " ",
            "url": "",
            "password": " ",
            "is_active": jsonData.twilio_status
        };
    });

    handleFormSubmission("#msg_nineone", ".msg_nineone_loader", function (jsonData) {
        return {
            "type": "msg_nineone",
            "name": " ",
            "api_id": "",
            "authkey": jsonData.authkey,
            "senderid": jsonData.senderid,
            "contact": "",
            "username": " ",
            "url": "",
            "password": " ",
            "is_active": jsonData.msg_nineone_status
        };
    });

    handleFormSubmission("#text_local", ".text_local_loader", function (jsonData) {
        return {
            "type": "text_local",
            "name": " ",
            "api_id": " ",
            "authkey": " ",
            "senderid": jsonData.text_localsenderid,
            "contact": "",
            "username": jsonData.text_local,
            "url": "",
            "password": jsonData.text_localpassword,
            "is_active": jsonData.text_local_status
        };
    });

    handleFormSubmission("#smscountry", ".smscountry_loader", function (jsonData) {
        return {
            "type": "smscountry",
            "name": " ",
            "api_id": " ",
            "authkey": " ",
            "senderid": jsonData.smscountrysenderid,
            "contact": "",
            "username": jsonData.smscountry,
            "url": "",
            "password": jsonData.smscountrypassword,
            "is_active": jsonData.smscountry_status
        };
    });

    handleFormSubmission("#bulk_sms", ".bulk_sms_loader", function (jsonData) {
        return {
            "type": "bulk_sms",
            "name": " ",
            "api_id": " ",
            "authkey": " ",
            "senderid": " ",
            "contact": "",
            "username": jsonData.bulk_sms_user_name,
            "url": "",
            "password": jsonData.bulk_sms_user_password,
            "is_active": jsonData.bulk_sms_status
        };
    });

    handleFormSubmission("#mobireach", ".mobireach_loader", function (jsonData) {
        return {
            "type": "mobireach",
            "name": " ",
            "api_id": jsonData.mobireach_route_id,
            "authkey": jsonData.mobireach_auth_key,
            "senderid": jsonData.mobireach_sender_id,
            "contact": "",
            "username": "",
            "url": "",
            "password": "",
            "is_active": jsonData.mobireach_status
        };
    });

    handleFormSubmission("#nexmo", ".nexmo_loader", function (jsonData) {
        return {
            "type": "nexmo",
            "name": " ",
            "api_id": jsonData.nexmo_api_key,
            "authkey": jsonData.nexmo_api_secret,
            "senderid": "",
            "contact": jsonData.nexmo_registered_phone_number,
            "username": "",
            "url": "",
            "password": "",
            "is_active": jsonData.nexmo_status
        };
    });

    handleFormSubmission("#africastalking", ".africastalking_loader", function (jsonData) {
        return {
            "type": "africastalking",
            "name": " ",
            "api_id": jsonData.africastalking_apikey,
            "authkey": "",
            "senderid": "",
            "contact": jsonData.africastalking_registered_phone,
            "username": jsonData.africastalking_short_code,
            "url": "",
            "password": "",
            "is_active": jsonData.africastalking_status
        };
    });

    handleFormSubmission("#custom", ".custom_loader", function (jsonData) {
        return {
            "type": "custom",
            "name": jsonData.custom_name,
            "api_id": jsonData.custom_api_id,
            "authkey": jsonData.custom_auth_key,
            "senderid": jsonData.custom_sender_id,
            "contact": "",
            "username": jsonData.custom_username,
            "url": jsonData.custom_url,
            "password": jsonData.custom_password,
            "is_active": jsonData.custom_status
        };
    });
</script>
