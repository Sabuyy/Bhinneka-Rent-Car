{%- macro renderSubmenu(menuList) -%}
<ul class="submenu-inner">
  {%- for menu in menuList -%} {%- set toggleClass = menu.has_children ? 'dropdown-toggle' : '' -%} {%- set href = menu.link_menu is not empty and not menu.has_children ? url(session.get('pdam').kode ~ '/' ~ menu.link_menu) : 'javascript:;' -%}
  <li class="nav-item" data-menu_id="{{ menu.id_menu }}">
    <a class="nav-link {{ toggleClass }}" href="{{ href }}">
      <span class="nav-text fadeable">{{ menu.nama_menu }}</span>

      {#
      <?php if(isset($menu->badge_id) && !empty($menu->badge_id)): ?>
      <span class="badge badge-danger py-1 radius-round text-90 mr-0 badge-sm" id="{{ menu.badge_id }}"></span>
      <?php endif; ?>
      #} {%- if menu.has_children -%}
      <b class="caret fa fa-angle-left rt-n90"></b>
      {%- endif -%}
    </a>
    {%- if menu.has_children -%}
    <div class="submenu collapse">
      {{ renderSubmenu(menu.children) }}
    </div>
    {%- endif -%}
  </li>
  {%- endfor -%}
</ul>
{%- endmacro -%}

<nav class="pt-3" aria-label="Main">
  <ul class="nav flex-column has-active-border">
    {% for menu in menuModel.getUserMenuList() %} {% set toggleClass = menu.link_menu is not empty ? '' : 'dropdown-toggle' %} {% set href =  menu.link_menu ? url(session.get('pdam').kode ~ '/' ~ menu.link_menu) : 'javascript:;' %}
    <li class="nav-item" data-menu_id="{{ menu.id_menu }}">
      <a href="{{href}}" class="nav-link {{ toggleClass }}">
        <i class="nav-icon fa {{ menu.icon }}"></i>
        <span class="nav-text fadeable">
          {{ menu.nama_menu }}
        </span>
        {#
        <?php if(isset($menu->badge_id) && !empty($menu->badge_id)): ?>
        <span class="badge badge-danger py-1 radius-round text-90 mr-0 badge-sm" id="{{ menu.badge_id }}"></span>
        <?php endif; ?>
        #} {% if menu.has_children %}
        <b class="caret fa fa-angle-left rt-n90"></b>
        {% endif %}
      </a>
      {% if menu.has_children %}
      <div class="hideable submenu collapse">
        {{ renderSubmenu(menu.children) }}
      </div>
      {% endif %}
    </li>
    {% endfor %}
  </ul>
</nav>

<script>
  {% include "template/navigation-dynamic.js" %}
</script>
