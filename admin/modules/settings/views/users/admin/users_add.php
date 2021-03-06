<form class="form-horizontal" role="form" id="form-add" method="post">    
    <div id="showerror"></div>
    <div class="form-group">
        <label for="first_name" class="col-sm-3 control-label">First Name</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" id="first_name" name="first_name">
        </div>
    </div>
    <div class="form-group">
        <label for="last_name" class="col-sm-3 control-label">Last Name</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" id="last_name" name="last_name">
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-sm-3 control-label">Email</label>
        <div class="col-sm-5">
            <input type="email" class="form-control" id="email" name="email">
        </div>
    </div>
    <div class="form-group">
        <label for="username" class="col-sm-3 control-label">Username</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="username" name="username">
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="col-sm-3 control-label">Password</label>
        <div class="col-sm-4">
            <input type="password" class="form-control" id="password" name="password">
        </div>
    </div>
    <div class="form-group">
        <label for="cat_code" class="col-sm-3 control-label">Group</label>
        <div class="col-sm-4">
            <?php
            echo form_dropdown('group_id', $group, null, 'class="form-control"');
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="user_parent_id" class="col-sm-3 control-label">User Parent</label>
        <div class="col-sm-4">
            <?php
            echo form_dropdown('user_parent_id', $user_parent, null, 'class="form-control"');
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="active" value="active"> Active
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="button" id="btnDialogSave" class="btn btn-primary">Create</button>
        </div>
    </div>
</form>
<script type="text/javascript">
    $('#btnDialogSave').click(function() {
        $(this).attr('disabled', 'disabled');
        loading_button();
        var data = {
            url: 'settings/result_users/add',
            v: $('#form-add, select input:not(#btnDialogSave)').serializeArray()
        };
        saveData(data);
    });
</script>