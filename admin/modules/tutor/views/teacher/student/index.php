            <div class="row">
            	<div class="col-xs-12">
            		<div class="box">
            			<div class="box-header">
            				<h3 class="box-title">Student Search</h3>
            			</div>
            			<div class="box-body">
            				<form class="form-horizontal" method="post" id="form-search" action="<?php echo base_url().index_page(); ?>tutor/student/export/excel">
            					<div class="form-group">
            						<label for="coursecode" class="col-sm-3 control-label">Name</label>
            						<div class="col-sm-4">
            							<input type="text" class="form-control" id="text_search" name="text_search">
            						</div>
            					</div>
            					<div class="form-group">
            						<label for="branch_id" class="col-sm-3 control-label">Branch</label>
            						<div class="col-sm-3">
            							<?php
            							echo form_dropdown('branch_id', $ddl_branch, null, 'class="form-control" id="branch_id"');
            							?>
            						</div>
            					</div>
            					<div class="form-group">
            						<label for="course_id" class="col-sm-3 control-label">Course</label>
            						<div class="col-sm-5">
            							<select name="course_id" id="course_id" class="form-control">
            								<option selected="selected" value=""></option>                            
            							</select>
            						</div>
            					</div>
            					<div class="form-group">
            						<div class="col-sm-offset-3 col-sm-9">
            							<div class="checkbox">
            								<label>
            									<?php echo form_checkbox('stuold', 1, false,'id="stuold"'); ?> นักเรียนเก่า
            								</label>
            							</div>
            						</div>
            					</div>
            					<div class="form-group">
            						<div class="col-sm-offset-3 col-sm-9">
            							<div class="checkbox">
            								<label>
            									<?php echo form_checkbox('private', 1, false,'id="private"'); ?> Private
            								</label>
            							</div>
            						</div>
            					</div>
            					<div class="form-group">
            						<div class="col-sm-offset-3 col-sm-9">
            							<button type="button" id="btnSearch" class="btn btn-primary"><i class="fa fa-search"></i> Search </button>
            						</div>
            					</div>
            				</form>
            			</div> 
            		</div>
            	</div>
            </div>
            <div class="row">
            	<div class="col-xs-12">
            		<div class="box">
            			<div class="box-body">
            				<table class="table table-striped table-bordered table-hover" id="student_listall"></table>
            			</div> 
            		</div>
            	</div>
            </div>
            <script type="text/javascript">
            	$(document).ready(function() {
            		$('#branch_id').change(function () {
            			$.get(site_url + 'common/get_course_ddl/', {branch_id: $(this).val()},
            				function (data) {
            					var as = JSON.parse(data);
            					if (as) {
            						var course_id = $('#course_id');
            						course_id.empty();
            						$.each(as, function (index, element) {
            							course_id.append("<option value='" + index + "'>" + element + "</option>");
            						});
            					}
            				});
            		});
            		
            		var oTable = $('#student_listall').dataTable({
            			"processing": true,
            			"serverSide": true,
            			"deferLoading": 0,
            			"ajax": {
            				"url": site_url + "tutor/student/listall",
            				"type": "POST",
            				"data": function (d) {
            					d.text_search = $('#text_search').val();
            					d.branch_id = $('#branch_id').val();
            					d.course_id = $('#course_id').val();
            					d.stuold = ($('#stuold').prop('checked')?1:0);
            					d.private = ($('#private').prop('checked')?1:0);
            				}
            			},
            			"language": {
            				"url": "//cdn.datatables.net/plug-ins/1.10.9/i18n/Thai.json"
            			},
            			"aoColumns": [
            			{"mData": "id","title": "", "width": "2%", "sClass": "text-center","orderable": false, "searchable": false},
            			{"mData": "code_member","title": "Code", "width": "8%","orderable": false, "searchable": true},
            			{"mData": "full_name","title": "Full Name", "width": "20%","orderable": false, "searchable": true},
            			{"mData": "email","title": "Email", "width": "20%","orderable": false, "searchable": true},
            			{"mData": "parent_phone","title": "Phone", "width": "10%","orderable": false, "searchable": true},
            			{"mData": "private","title": "Private", "width": "5%", "sClass": "text-center","orderable": false, "searchable": false},
            			{"mData": "active","title": "Active", "width": "5%", "sClass": "text-center","orderable": false, "searchable": false}
            			],
            			"sDom": 'ltipr'
            		});

            		$('#btnSearch').click(function () {
            			if ($("#form-search").valid()) {
            				oTable.fnDraw();
            			}
            		});

            		$('#myModal').on('hidden.bs.modal', function () {
            			oTable.ajax.reload();
            		});
            	});
            </script>