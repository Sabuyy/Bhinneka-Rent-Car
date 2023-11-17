<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">

  <title>{% block title %}{% endblock %} - {{ config.appName }}</title>

  <!-- include common vendor stylesheets & fontawesome -->
  <link rel="stylesheet" type="text/css" href="{{ url('assets') }}/node_modules/bootstrap/dist/css/bootstrap.css">

  <link rel="stylesheet" type="text/css" href="{{ url('assets') }}/node_modules/@fortawesome/fontawesome-free/css/fontawesome.css">
  <link rel="stylesheet" type="text/css" href="{{ url('assets') }}/node_modules/@fortawesome/fontawesome-free/css/regular.css">
  <link rel="stylesheet" type="text/css" href="{{ url('assets') }}/node_modules/@fortawesome/fontawesome-free/css/brands.css">
  <link rel="stylesheet" type="text/css" href="{{ url('assets') }}/node_modules/@fortawesome/fontawesome-free/css/solid.css">

  <link rel="stylesheet" type="text/css" href="{{ url('assets') }}/node_modules/select2/dist/css/select2.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">

  <!-- include fonts -->
  <link rel="stylesheet" type="text/css" href="{{ url('assets') }}/dist/css/ace-font.css">
  <link rel="stylesheet" type="text/css" href="{{ url('assets') }}/css/src/bootstrap-datepicker3.min.css">
  <link rel="stylesheet" type="text/css" href="{{ url('assets') }}/css/src/bootstrap-datetimepicker.min.css">

  <!-- ace.css -->
  <link rel="stylesheet" type="text/css" href="{{ url('assets') }}/dist/css/ace.css">
  {% block styles %}
  {% endblock %}

  <!-- favicon -->
  <!-- Fav Icon based on PDAM -->
  {# <link rel="icon" type="image/png" id="fav_icon"/> #}
  <link rel="icon" type="image/png" href="{{ url('assets') }}/image/tjm.png" />

  <!-- "Login" page styles, specific to this page for demo only -->
  {% block inline_style %}
  <style>
    .body-container {
      background-image: linear-gradient(#6baace, #264783);
      background-attachment: fixed;
      background-repeat: no-repeat;
    }
  </style>
  {% endblock %}
</head>

<body>
  <div class="body-container">

    <div class="main-container container bgc-transparent">

      <div class="main-content minh-100 justify-content-center">
        {% block content %}
        {% endblock %}
      </div>
    </div>
  </div>
  <script src="{{ url('assets') }}/node_modules/jquery/dist/jquery.js"></script>
  <script src="{{ url('assets') }}/node_modules/popper.js/dist/umd/popper.js"></script>
  <script src="{{ url('assets') }}/node_modules/bootstrap/dist/js/bootstrap.js"></script>
  <script src="{{ url('assets') }}/js/src/bootstrap-datepicker.js"></script>
  <script src="{{ url('assets') }}/js/src/bootstrap-datetimepicker.min.js"></script>
  <script src="{{ url('assets') }}/node_modules/select2/dist/js/select2.min.js"></script>
  
	

  <!-- include ace.js -->
  <script src="{{ url('assets') }}/dist/js/ace.js"></script>
  {% block scripts %}
  {% endblock %}
  
  {% block inline_script %}
  {% endblock %}

<script>
  $(".yearpicker").datepicker({
    format: "yyyy",
    minViewMode: 'years', // or 1, 月选择
    startView: 'decade', // or 2, 10年选择
    autoclose: true,
  }).on('input change select', function (e) {
    // $(this).valid();
    $(e.currentTarget).data('datepicker').hide();
  });
</script>
  
</body>
</html>