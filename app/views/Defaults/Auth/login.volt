{% extends 'template/auth.volt' %} {% block title %} Login - {{ config.appName }}
{% endblock %} {% block inline_style %}
<style>
  .body-container {
    background: linear-gradient(to right, #0e0e0ecc, #0e0e0ecc), url("{{ url('assets') }}/image/bg-office.jpg");
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-position: center;
  }
</style>
{% endblock %} {% block content %}
<div class="p-2 p-md-2">
  <div class="row">
    <div class="col-6 offset-3 bgc-white shadow radius-1 overflow-hidden py-5">
      <div class="mh-100">
        <div class="row justify-content-center">
          <div class="col-12 mt-2">
            <img class="mx-auto d-block" src="{{ url('assets') }}/image/tjm.png" alt="LOGO {{ config.appName }}" style="height: 200px" />
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-12 mt-2">
            <div class="text-center font-bold fa-2x text-primary">
              {{ pdam.nama_pdam }}
              <h6>({{ config.appLongName }})</h6>
            </div>
          </div>
        </div>
        <div class="mh-100 offset-md-3"></div>
        <div class="d-none d-lg-block col-md-6 offset-md-3 mt-lg-4 px-0">
          <?php if(!empty($this->request->get('wp')) == "true"){ ?>
          <div class="alert d-flex bgc-red-l3 brc-success-m4 border-0 p-0" role="alert">
            <div class="bgc-red px-3 py-1 text-center radius-l-1">
              <span class="text-white">
                ⚠
                <!-- &#9888; -->
              </span>
            </div>
            <span class="ml-3 align-self-center text-dark-tp3"> Username atau Password Salah </span>
          </div>
          <?php } elseif(!empty($this->request->get('wpc')) == "true") { ?>
          <div class="alert d-flex bgc-red-l3 brc-success-m4 border-0 p-0" role="alert">
            <div class="bgc-red px-3 py-1 text-center radius-l-1">
              <span class="text-white">
                ⚠
                <!-- &#9888; -->
              </span>
            </div>
            <span class="ml-3 align-self-center text-dark-tp3"> Akun anda tidak diijinkan untuk akses sistem ini </span>
          </div>
          <?php } elseif(!empty($this->request->get('wy')) == "true") { ?>
          <div class="alert d-flex bgc-red-l3 brc-success-m4 border-0 p-0" role="alert">
            <div class="bgc-red px-3 py-1 text-center radius-l-1">
              <span class="text-white">
                ⚠
                <!-- &#9888; -->
              </span>
            </div>
            <span class="ml-3 align-self-center text-dark-tp3"> Tahun Masih Kosong </span>
          </div>
          <?php } ?>
        </div>
      </div>
      <form autocomplete="off" class="form-row mt-4" method="post">
        <input name="pdam" id="pdam" value ="{{ pdam.pdam_id }}" type="hidden">

        <div class="form-group col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
          <div class="d-flex align-items-center input-floating-label text-blue brc-blue-m2">
            <input name="username" placeholder="Username" type="text" class="form-control form-control-lg pr-4 shadow-none" id="id-login-username" required />
            <i class="fa fa-user text-grey-m2 ml-n4"></i>
            <label class="floating-label text-grey-l1 ml-n3" for="id-login-username"> Username </label>
          </div>
        </div>

        <div class="form-group col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3 mt-2 mt-md-1">
          <div class="d-flex align-items-center input-floating-label text-blue brc-blue-m2">
            <input name="password" placeholder="Password" type="password" class="form-control form-control-lg pr-4 shadow-none" id="id-login-password" required />
            <i class="fa fa-key text-grey-m2 ml-n4"></i>
            <label class="floating-label text-grey-l1 ml-n3" for="id-login-password"> Password </label>
          </div>
        </div>

      
        <div class="form-group col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3 mt-2 mt-md-1">
          <div class="d-flex align-items-center input-floating-label text-blue brc-blue-m2">
            <input name="tahun" placeholder="Tahun" type="text" class="form-control form-control-lg pr-4 shadow-none yearpicker" id="id-login-tahun" value="<?= date("Y")+1 ?>"/>
            <i class="fa fa-calendar text-grey-m2 ml-n4"></i>
            <label class="floating-label text-grey-l1 ml-n3" for="id-login-tahun"> Tahun </label>
          </div>
        </div>
       

        <div class="form-group col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
          <button type="submit" class="btn btn-primary btn-block px-4 btn-bold mt-2 mb-4">Sign In</button>
        </div>
      </form>
    </div>
  </div>
</div>
{% endblock %}
{% block inline_script %}
<script>
    {% include 'Defaults/Auth/login.js' %}
</script>
{% endblock %}
