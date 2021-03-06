<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">ที่อยู่จัดส่งสินค้า</h1>
    </div>
</div>
<div class="row">    
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="stepy-tab">
                    <ul class="stepy-titles clearfix">
                        <li><a href="<?php echo $link_wizad['step1']; ?>"><div>เลือกสินค้า</div><span> </span></a></li>
                        <li><a href="<?php echo $link_wizad['step2']; ?>"><div>ตะกร้าสินค้า</div><span> </span></a></li>
                        <li class="current-step"><div>ที่อยู่จัดส่งสินค้า</div><span> </span></li>
                        <li><a href="<?php echo $link_wizad['step4']; ?>"><div>วิธีชำระเงิน</div><span> </span></a></li>
                        <li><a href="<?php echo $link_wizad['step5']; ?>"><div>ยืนยันการสั่งซื้อ</div><span> </span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<form class="form-horizontal" role="form" id="form-add" method="get">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>เลือกที่อยู่จัดส่ง</strong>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="first_name" class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <?php
                            if (count($address) > 0) {
                                foreach ($address as $item) {
                                    ?>
                                    <div class="radio">
                                        <label>
                                            <?php echo form_radio('adds', $item['id'], ($item['id'] == $this->input->cookie('address_id') ? TRUE : FALSE), 'class="address_radio"'); ?> <?php echo $item['address']; ?>

                                        </label>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo 'No data';
                            }
                            ?>
                        </div>
                    </div>     
                    <div class="form-group">
                        <label for="first_name" class="col-sm-2 control-label"></label>
                        <div class="col-sm-8">
                            <a href="#" class="btn btn-primary btn-xl link_dialog" role="button" rel="users/backend/user/add_address" title="Add Address"> เพิ่มที่อยู่ </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>ใบกำกับภาษี</strong>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="first_name" class="col-sm-2 control-label"></label>
                        <div class="col-sm-8">
                            <div class="checkbox">
                                <label>
                                    <?php echo form_checkbox('tax_1', 1, ($this->input->cookie('tax_1', true) ? TRUE : FALSE), 'id="tax_1"'); ?> ใบเสร็จรับเงิน                                    
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <?php echo form_checkbox('tax_2', $this->ion_auth->get_user_id(), ($this->input->cookie('tax_2', true) ? TRUE : FALSE), 'id="tax_2"'); ?> ใบกำกับภาษี (ฉบับเต็ม)
                                </label>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>เลือกรูปแบบการจัดส่ง</strong>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="shipping_id" class="col-sm-2 control-label"></label>
                        <div class="col-sm-3">
                            <?php echo form_dropdown('shipping_id', $shipping_type, ($this->input->cookie('shipping_id', true) ? $this->input->cookie('shipping_id') : NULL), 'class="form-control" id="shipping_id"'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class = "col-lg-12">
            <div class = "panel-body">
                <div class = "text-center">
                    <a href = "<?php echo $link_wizad['step2']; ?>" class = "btn btn-success btn-lg" role = "button">
                        ย้อนกลับ <i class = "fa fa-arrow-circle-left"></i></a>
                    <a href = "<?php echo $link_wizad['step4']; ?>" class = "btn btn-success btn-lg" role = "button">
                        <i class = "fa fa-arrow-circle-right"></i> ถัดไป</a>
                </div>
            </div>
        </div>
    </div>
</form>
<script type = "text/javascript">
    $('.address_radio').click(function() {
        $.cookie('address_id', $(this).val());
    });

    $('#tax_1').click(function() {
        if ($(this).is(":checked")) {
            $.cookie('tax_1', $(this).val());
        } else {
            $.cookie('tax_1', '');
        }
    });

    $('#tax_2').click(function() {
        if ($(this).is(":checked")) {
            $.cookie('tax_2', $(this).val());
        } else {
            $.cookie('tax_2', '');
        }
    });

    $('#shipping_id').change(function() {
        $.cookie('shipping_id', $(this).val());
    });
</script>