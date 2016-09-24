<?php
class View {
	
	const TEMPLATE_VIEW = 'template_main';
	
	function generate($content_view, $template_view = self::TEMPLATE_VIEW, $data = null) {
		if(is_array($data)) {
			extract($data);
		}
		include "${_SERVER['DOCUMENT_ROOT']}/views/$template_view.php";
	}
}
