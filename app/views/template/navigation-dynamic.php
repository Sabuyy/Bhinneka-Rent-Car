<?php

use Core\Facades\Url;
use App\Models\MenuModel;

$menuList = MenuModel::getUserMenuList();
if (!function_exists('renderSubmenu')) {
	function renderSubmenu($menus)
	{
		$nav = '<ul class="submenu-inner">';
		foreach ($menus as $menu) {
			$nav .= '<li class="nav-item" data-menu_id="' . $menu->id . '">';
			$href = !empty($menu->path) && empty($menu->children) ? Url::get($menu->path) : 'javascript:;';
			$toggle = $menu->has_children && !empty($menu->children) ? 'dropdown-toggle' : '';
			$nav .= '<a class="nav-link ' . $toggle . '" href="' . $href . '">';
			$nav .= '<span class="nav-text fadeable">' . $menu->name . '</span>';
			if ($menu->has_children && !empty($menu->children)) {
				$nav .= '<b class="caret fa fa-angle-left rt-n90"></b>';
			}
			$nav .= '</a>';
			if ($menu->has_children && !empty($menu->children)) {
				$nav .= '<div class="submenu collapse">';
				$nav .= renderSubmenu($menu->children);
				$nav .= '</div>';
			}
			$nav .= '</li>';
		}
		$nav .= '</ul>';
		return $nav;
	}
}
?>
<nav class="pt-3" aria-label="Main">
	<ul class="nav flex-column has-active-border">
		<?php foreach ($menuList as $menu) : ?>
			<li class="nav-item" data-menu_id="<?= $menu->id ?>">
				<?php $toggleClass = empty($menu->path) ? ' class="nav-link dropdown-toggle"' : ' class="nav-link"'; ?>
				<a href="<?= $menu->path ? $this->url->get($menu->path) : 'javascript:;'; ?>" <?= $toggleClass ?>>
					<i class="nav-icon fa <?= $menu->icon ?>"></i>
					<span class="nav-text fadeable">
						<?= $menu->name ?>
					</span>
					<?php if (isset($menu->children) && !empty($menu->children)) : ?>
						<b class="caret fa fa-angle-left rt-n90"></b>
					<?php endif; ?>
				</a>
				<?php if (isset($menu->children) && !empty($menu->children)) : ?>
					<div class="hideable submenu collapse">
						<?= renderSubmenu($menu->children) ?>
					</div>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
<script>
	(function() {
		let elements = document.querySelectorAll('#sidebar li.nav-item > a');
		let found = null;
		for (let el of elements) {
			const url = window.location.toString();
			if (url.startsWith(el.href)) {
				if (found == null || found.href.length < el.href.length) {
					found = el;
				}
			}
		}

		function setDeepNavActive(element) {
			let parent = element.parentElement;
			let firstLIFound = false;
			while (parent != null && parent.tagName != 'NAV') {
				if (parent.tagName == 'LI' && parent.classList.contains('nav-item')) {
					parent.classList.add('active');
					if (firstLIFound) {
						parent.classList.add('open');
					}
					firstLIFound = true;
				}
				if (parent.tagName == 'DIV' && parent.classList.contains('collapse')) {
					parent.classList.add('show');
				}
				parent = parent.parentElement;
			}
		}

		if (found) {
			setDeepNavActive(found);
		}
	})();
</script>