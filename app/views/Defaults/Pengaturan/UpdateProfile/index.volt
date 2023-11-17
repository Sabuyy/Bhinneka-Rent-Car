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
				Pengaturan - Update Profile PDAM
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
                                <div class="row">
                                    <div class="col-sm-12 mb-5 mt-5" style="margin-bottom : 5px;">
                                        <div class="text-center">
                                            <img src="{{ url('assets') }}/logo/chpn.png" id="foto-profile" class="rounded-circle shadow-4-strong img-fluid" alt="..." style="width:180px;height:180px">
                                        </div>
                                    </div>
                                </div>
                                <div id="el_moveFotoProfile">
                                    <div id="el_fotoProfile" class="form-group row">
                                        <div class="input-group col-sm-12">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="width: 180px;">Ubah Foto Profil</span>
                                            </div>
                                            <input type="file" class="form-control" id="foto_profile" name="foto_profile" accept="image/png, image/jpeg" onchange="fotoUpload(this)">
                                            <input type="hidden" class="form-control" id="foto_profile_text" name="foto_profile_text">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-danger"  id="btn-cancel">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-12" style="margin-bottom : 5px;">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="width: 180px;">Nama PDAM</span>
                                            </div>
                                            <input type="text" id="nama_update" name="nama_update" class="form-control" value="{{ data.nama_pdam }}" required>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="row form-group">
                                    <div class="col-sm-12" style="margin-bottom : 5px;">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="width: 180px;">Kota / Kabupaten</span>
                                            </div>
                                            <input type="text" id="kota_kab_update" name="kota_kab_update" class="form-control" required value="{{ data.kota_kab_pdam }}">
                                        </div>
                                    </div>
                                </div>
        
                                <div class="row form-group">
                                    <div class="col-sm-12" style="margin-bottom : 5px;">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="width: 180px;">Alamat PDAM</span>
                                            </div>
                                            <input type="text" id="alamat_update" name="alamat_update" class="form-control" value="{{ data.alamat_pdam }}"required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-12" style="margin-bottom : 5px;">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="width: 180px;">No Telpon</span>
                                            </div>
                                            <input type="text" id="telp_update" name="telp_update" class="form-control" required value="{{ data.no_telp_pdam }}">
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
	{% include 'Defaults/Pengaturan/UpdateProfile/index.js' %}
</script>
{% endblock %}