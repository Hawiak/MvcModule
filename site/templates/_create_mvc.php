<?php
if($this->page->template->fields->get('mvc')){
	//MVC is enabled for this template and therefor page;
	if(isset($input->urlSegments[1]) ? $action = $input->urlSegments[1] : $action = 'index');
	$controller = $page->template->name;
	$controller_path = $config->paths->root . $mvc->controllers_path. "/" .$controller.'.php';
	if(file_exists($controller_path)){
		$controller_class = ucfirst($controller).'Controller';
		if(class_exists($controller_class)){
			$mvc->controller = new $controller_class();
			$mvc->set_action($action);
			$mvc->set_view_root(ucfirst($page->template->name));
			$mvc->render();			
		}else{
			echo 'Class ' . $controller_class .' does not exist in ' .$controller_path;
		}
	}else{
		echo 'The file ' . $controller_path . ' does not exist';
	}

}
