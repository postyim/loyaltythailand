<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">ชำระเงิน</h1>
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
                        <li><a href="<?php echo $link_wizad['step3']; ?>"><div>ที่อยู่จัดส่งสินค้า</div><span> </span></a></li>
                        <li class="current-step"><div>วิธีชำระเงิน</div><span> </span></li>
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
                    <strong>เลือกรูปแบบการชำระเงิน</strong>
                </div>
                <div class="panel-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <?php
                        $i = 0;
                        foreach ($payment_type as $item) {
                            ?>
                            <li class="<?php echo ($i === 0 ? 'active' : ''); ?>"><a href="#tab_<?php echo $i; ?>" role="tab" data-toggle="tab"><?php echo $item->title; ?></a></li>
                            <?php
                            $i++;
                        }
                        ?>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php
                        $j = 0;
                        $k = 0;
                        foreach ($payment_type as $item2) {
                            ?>
                            <div class="tab-pane <?php echo ($j === 0 ? 'active' : ''); ?>" id="tab_<?php echo $j; ?>">                                
                                <?php
                                $payment_item = $this->Cart_model->get_payment_list($item2->id);
                                foreach ($payment_item as $key => $value) {
                                    ?>
                                    <div class="bs-callout bs-callout-info">
                                        <h4><?php echo $value->title; ?></h4>
                                        <div class="radio">
                                            <label>
                                                <?php
                                                if ($this->input->cookie('payment_id', true) === FALSE) {
                                                    if (($key === 0) and ( $j === 0)) {
                                                        $ck = TRUE;
                                                    } else {
                                                        $ck = FALSE;
                                                    }
                                                } else {
                                                    $ck = ($this->input->cookie('payment_id', true) == $value->id ? TRUE : FALSE);
                                                }
                                                echo form_radio('payment_id', $value->id, $ck, 'class="payment_id"');
                                                ?> <strong>ชำระผ่านธนาคาร<?php echo $value->title; ?></strong>
                                            </label>
                                        </div>
                                        <?php echo $value->description; ?>
                                    </div>
                                    <?php
                                    $k++;
                                }
                                ?>
                            </div>
                            <?php
                            $j++;
                        }
                        ?>
                    </div>  
                </div>
            </div>
        </div>        
        <div class="col-lg-12">
            <div class="panel-body">
                <div class="text-center">                   
                    <a href="<?php echo $link_wizad['step3']; ?>" class="btn btn-success btn-lg" role="button">
                        ย้อนกลับ <i class="fa fa-arrow-circle-left"></i></a>  
                    <a href="<?php echo $link_wizad['step5']; ?>" class="btn btn-success btn-lg" role="button">
                        <i class="fa fa-arrow-circle-right"></i> ถัดไป</a>  
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(function() {
        if ($('.payment_id:checked').val()) {
            $.cookie('payment_id', $('.payment_id:checked').val());
        }
    });

    $('input[type=radio]').click(function() {
        $.cookie('payment_id', $(this).val());
    });
    $('#btnAddAddress').click(function() {
        var data = {url: 'users/backend/user/add_address',
            title: 'Add Address'
        };
        genModal(data);
    });
</script>