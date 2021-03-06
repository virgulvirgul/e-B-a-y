<?php
/**
 * role setting tree component
 * 
 * @author Bob <Foxzeng>
 */
if ( User::isAdmin()) {
    $list = Role::getTreeList();
} else {
    $roles = User::getLoginUserRoles();
    $list = Role::getTreeList();
}
$menuId = isset($menuId) ?  $menuId : '';
$pairs = Role::getPairs();
//echo $menuIdl;
echo '<ul class="'. $class .' " id="'. $id .'"><li>';
echo CHtml::link($root, 'javascript:void(0);', User::isAdmin() ? array( "id" => 'roleAccessId_all') : array());
echo '<ul>';
if(strpos($menuId,' ')){//传来的menuId值单词间空格改成'_'，
	$menuId = str_replace(' ', '_', $menuId);
}

$disableOption = User::isAdmin() ? false : true;//默认不能修改同级角色
if ( isset( $hasroleIds) ) {    
    echo subMenu($list, $pairs, $hasroleIds, $menuId, $disableOption);
} else {   
    echo subMenu($list, $pairs,'',$menuId, $disableOption);
}
echo '</ul></li></ul>';
function subMenu($data, $pairs = null, $hasroleIds = null,$menuId=null, $disableOption=false) {  
    $str = '';
    foreach ($data as $key => $val) {
    	if($val==null){
    		continue;
    	}
        $str .= "<li>";  
        if($disableOption){//不能操作权限的角色
        	$htmlOptions = array();
        }else{
        	//$htmlOptions = array( "id" => 'roleAccessId_'.$val['child']);
        	$htmlOptions = array( "id" => $val['child'], "class" => 'roleAccessId_'.$val['child']);
        }
		if ( $hasroleIds && in_array($val['child'], $hasroleIds)) {
            // $AuthAssignment = AuthAssignment::model()->getUlist($val['child']);
			// $htmlOptions['checked'] = true;
		}
        
		
        $str .= CHtml::link($pairs[$val['child']], 'javascript:void(0);', $htmlOptions); 
        if ( isset($val['children']) && $val['child']!=$menuId) {
            $str .= "<ul >";
			// if(count($AuthAssignment)>0){
				// foreach ($AuthAssignment as $key2 => $val2) {
				   // $str .= "<li style='margin-left: 115px;'>".$val2['user_full_name']."</li>";
			    // }
			// }
            $str .= subMenu($val['children'], $pairs, $hasroleIds,$menuId);
            $str .= "</ul>";
        }else{
			// if ( $hasroleIds && in_array($val['child'], $hasroleIds)) {
				// $str .= "<ul style='margin-left: 115px;'>";
				  // foreach ($AuthAssignment as $key2 => $val2) {
					   // $str .= "<li>".$val2['user_full_name']."</li>";
				 // }
				 // $str .= "</ul>";
		    // }
		}
        		
        $str .= '</li>';
    }
	
    return $str;
}
?>

