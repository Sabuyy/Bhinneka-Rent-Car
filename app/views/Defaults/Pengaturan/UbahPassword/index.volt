{% extends 'template/dashboard.volt' %}
{% block title %}Pengaturan User
{% endblock %}
{% block content %}
<style>
	.select2-container--bootstrap4 .select2-selection--single {
		height: calc(3em) !important;
	}
</style>
<div class="page-content" >
	<div class="card ccard mx-auto" style="width: 98%; position: sticky;">
		<div class="card-header pb-1 align-middle border-t-3 brc-primary-tp3" style="border-top-left-radius: 0.4rem;
		border-top-right-radius: 0.4rem;border-bottom: 1px solid #e0e5e8 !important;" >
			<h4 class="card-title text-dark-m3 mt-2">
				Pengaturan - Ganti Password
			</h4>
			<div class="page-tools mt-3 mt-sm-0 mb-sm-n1 card-toolbar">

				
			</div>
		</div>

		<div class="card-body p-3">
			<div class="row">
				<div class="col-md-12" id="formModal">
					<form class="form-horizontal" id="form-data" action="javascript:;">
    
                        <div class="row">
                            <div class="col-3"></div>
                            <div class="col-6">
                                <div class="row form-group">
                                    <div class="col-sm-12" style="margin-bottom : 5px;">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="width: 180px;">Password Lama</span>
                                            </div>
                                            <input type="password" id="password_old" name="password_old" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="row form-group">
                                    <div class="col-sm-12" style="margin-bottom : 5px;">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="width: 180px;">Password Baru</span>
                                            </div>
                                            <input type="password" id="password_new" name="password_new" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="row form-group">
                                    <div class="col-sm-12" style="margin-bottom : 5px;">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="width: 180px;">Konfirmasi Password Baru</span>
                                            </div>
                                            <input type="password" id="password_confirm" name="password_confirm" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
    
                                
                                <button type="submit" id="btn-submit" class="btn btn-success radius-2">
                                    <i class="fas fa-save"></i>
                                    Simpan
                                </button>
                            </div>
                            <div class="col-3"></div>
                        </div>
                    </form>

				</div>
			</div>
		</div>

	</div>
</div>
{% endblock %}
{% block inline_script %}
<script>
	{% include 'Defaults/Pengaturan/UbahPassword/index.js' %}
</script>
{% endblock %}