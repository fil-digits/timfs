<?php namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDbooster;
use Excel;
//use App\Channel;

class AdminCmsUsersController extends \crocodicstudio\crudbooster\controllers\CBController {

	public function __construct() {
		DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
	}
	
	public function cbInit() {
		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->table               = 'cms_users';
		$this->primary_key         = 'id';
		$this->title_field         = "name";
		$this->button_action_style = 'button_icon';	
		$this->button_import 	   = FALSE;	
		$this->button_export 	   = CRUDBooster::isSuperadmin() ? TRUE: FALSE;
		# END CONFIGURATION DO NOT REMOVE THIS LINE
	
		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = array();
		$this->col[] = array("label"=>"Full Name","name"=>"name");
		$this->col[] = array("label"=>"Email","name"=>"email");
		$this->col[] = array("label"=>"Privilege","name"=>"id_cms_privileges","join"=>"cms_privileges,name");
		$this->col[] = array("label"=>"Photo","name"=>"photo","image"=>1);
		$this->col[] = array("label"=>"Status","name"=>"status");
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = array();
		if(CRUDBooster::isSuperadmin() || CRUDBooster::myPrivilegeName() == "Admin") {
			$this->form[] = array("label"=>"First Name","name"=>"first_name",'required'=>true,'validation'=>'required|min:2', 'width'=>'col-sm-4');
			$this->form[] = array("label"=>"Last Name","name"=>"last_name",'required'=>true,'validation'=>'required|min:2', 'width'=>'col-sm-4');		
			//$this->form[] = array("label"=>"Full Name","name"=>"name",'required'=>true,'width'=>'col-sm-4','validation'=>'required|min:3');
			$this->form[] = array("label"=>"Email","name"=>"email",'required'=>true,'width'=>'col-sm-4','type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId());		
			$this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload",'width'=>'col-sm-4',"help"=>"Recommended resolution is 200x200px",'validation'=>'image|max:1000');											
			$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select",'width'=>'col-sm-4',"datatable"=>"cms_privileges,name",'required'=>true);	
			$this->form[] = array("label"=>"Password","name"=>"password","type"=>"password",'width'=>'col-sm-4',"help"=>"Please leave empty if not changed");
			if(in_array(CRUDBooster::getCurrentMethod(), ['getEdit','postEditSave'])) {
				$this->form[] = ['label'=>'Status','name'=>'status','type'=>'select','validation'=>'required','width'=>'col-sm-4','dataenum'=>'ACTIVE;INACTIVE'];
			}
		}

		if(in_array(CRUDBooster::getCurrentMethod(), ['getEdit','postEditSave','getProfile','getDetail']) && CRUDBooster::isUpdate() && !CRUDBooster::isSuperadmin()) {
			$this->form[] = array("label"=>"First Name","name"=>"first_name",'required'=>true, 'readonly'=>true,'validation'=>'required|min:2', 'width'=>'col-sm-4');
			$this->form[] = array("label"=>"Last Name","name"=>"last_name",'required'=>true, 'readonly'=>true,'validation'=>'required|min:2', 'width'=>'col-sm-4');
			$this->form[] = array("label"=>"Email","name"=>"email",'required'=>true, 'readonly'=>true,'width'=>'col-sm-4','type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId());		
			$this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload",'width'=>'col-sm-4',"help"=>"Recommended resolution is 200x200px", 'readonly'=>true,'validation'=>'required|image|max:1000');											
			$this->form[] = array("label"=>"Password","name"=>"password","type"=>"password",'width'=>'col-sm-4',"help"=>"Please leave empty if not changed");
		}
		# END FORM DO NOT REMOVE THIS LINE
		
		$this->button_selected = array();
        if(CRUDBooster::isUpdate() && (CRUDBooster::isSuperadmin() || CRUDBooster::myPrivilegeName() == "Admin")) {
        	$this->button_selected[] = ['label'=>'Set Status INACTIVE ','icon'=>'fa fa-check-circle','name'=>'set_status_INACTIVE'];
        	$this->button_selected[] = ['label'=>'Reset Password ','icon'=>'fa fa-reset','name'=>'reset_password'];
		}
	}

	public function getProfile() {			

		$this->button_addmore = FALSE;
		$this->button_cancel  = FALSE;
		$this->button_show    = FALSE;			
		$this->button_add     = FALSE;
		$this->button_delete  = FALSE;	
		$this->hide_form 	  = ['id_cms_privileges'];

		$data['page_title'] = trans("crudbooster.label_button_profile");
		$data['row'] = CRUDBooster::first('cms_users',CRUDBooster::myId());		
		$this->cbView('crudbooster::default.form',$data);				
	}

	public function hook_before_add(&$postdata) {        
	    //Your code here
	    if(is_null($postdata['photo'])) {
	    	$postdata['photo'] = 'uploads/1/2019-10/imfs_avatar.png';
	    }
		$postdata['status'] = 'ACTIVE';
		$postdata['name'] = $postdata['first_name'].' '.$postdata['last_name'];
		$postdata['user_name'] = $postdata['last_name'].''.substr($postdata['first_name'], 0, 1);
	}
	
	public function hook_before_edit(&$postdata, $id) {
		//Your code here
		$postdata['name'] = $postdata['first_name'] . ' ' . $postdata['last_name'];
		$postdata['user_name'] = $postdata['last_name'] . '' . substr($postdata['first_name'], 0, 1);
		$postdata['updated_by'] = CRUDBooster::myId();
	}

	public function hook_after_delete($id) {
		//Your code here
		DB::table('cms_users')->where('id', $id)->update(['status' => 'INACTIVE']);
	}

	public function actionButtonSelected($id_selected,$button_name) {
        //Your code here
		if($button_name == 'set_status_INACTIVE') {
			DB::table('cms_users')->whereIn('id',$id_selected)->update(['status'=>'INACTIVE']);	
		}
		elseif($button_name == 'reset_password') {
			DB::beginTransaction();
		    DB::table('cms_users')->whereIn('id',$id_selected)->update([
		    	'password'			=> bcrypt('qwerty'),
		    	'reset_password'	=> 1	
		    ]);
		    DB::commit();	
		}  
	}
	
	public function uploadUserAccountTemplate() {
		Excel::create('user-account-upload-'.date("Ymd").'-'.date("h.i.sa"), function ($excel) {
			$excel->sheet('useraccount', function ($sheet) {
				$sheet->row(1, array('FIRST NAME', 'LAST NAME', 'EMAIL', 'PRIVILEGE'));
				$sheet->row(2, array('John', 'Doe', 'johndoe@digits.ph','Requestor'));
			});
		})->download('csv');
	}
}
