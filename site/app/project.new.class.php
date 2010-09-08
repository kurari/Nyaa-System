<?php
/**
 * プロジェクト管理パッケージ
 * プロジェクト新規作成
 * ----
 *
 */
require_once dirname(__FILE__).'/project.class.php';

define('PROJECT_NEW_SQL_INSERT', <<<SQL
INSERT INTO project (uid, name, desc) VALUES (:uid, :name, :desc);
SQL
);

define('PROJECT_NEW_FORM', <<<FORM
[name]
label             = PROJECT

[desc]
type              = textarea
label             = 説明

[deadline]
type              = date
label             = 締切り

[submit]
type              = comp
child.save.type   = submit
child.save.label  = 登録
child.reset.type  = submit
child.reset.label = リセット
layout						= %html&nbsp;
layout_start			= <div class="buttons">
layout_end				= </div>
FORM
);

define('PROJECT_NEW_FORM_VALIDATER', <<<FORM
[nullcheck]
type=notnull
target.0 = name 
target.1 = desc
message = %fieldは必須です
message_sep = と
FORM
);
class ProjectNewApp extends ProjectApp
{
	protected $view = 'project.new.html';

	function init( )
	{
		$this->view = 'project.new.html';
		$this->db = $this->dbFactory('sqlite:localhost/'.$this->getConf('root.dir').'/data/project.db');
		$this->uid = $this->getSession('user.id');
		parent::init( );
	}

	function snipForm( )
	{
		$bind['form'] = $form  = $this->formFactory(PROJECT_NEW_FORM,'project.new.form','project.new.apply');
		$result = $this->getResult('project.new.form');
		if(!empty($result)){
			$bind['result'] = $result;
			$form->setValues($result->getOr('request', array()));
		}
		return  $bind;
	}

	function formApply( $Req )
	{
		$validater  = $this->validaterFactory(PROJECT_NEW_FORM_VALIDATER);
		$result = $validater->validate( $Req->get() );
		if( true !== $res = $validater->validate( $Req->get( ) ) ){
			foreach( $res as $e ) $errors[] = $e->getMessage( );
			return $this->resultError( $Req, $errors );
		}
		$sth = $this->db->prepare( PROJECT_NEW_SQL_INSERT );
		$sth->bindParam('uid', $this->uid);
		$sth->execute( $Req->get('name','desc') );
		$lastid = $sth->getLastId( );
		$opt = array('message'=>'#PROJECT_SAVED');
		return $this->resultRedirect('project.new',$opt);
	}
}
?>
