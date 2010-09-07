<?php
require_once dirname(__FILE__).'/profile.class.php';
class ProfilePhotoApp extends ProfileApp
{
	protected $view = 'profile.photo.html';

	function init( )
	{
		$this->db = $this->Ctrl->get('db.system');
		$this->db = NyaaDB::factory('sqlite:localhost/'.$this->Ctrl->getConf('root.dir').'/data/profile.photo.db');
		/*
		$this->db->query('drop table photo;');
		$this->db->query('create table photo (id integer primary key, userid int, name varchar(128), type varchar(128), size int(16), bin blob);');
		 */
		$this->type = "photo";

		$this->uid = $this->Ctrl->getSession()->get('user.id');

		$this->imageDir = $this->Ctrl->getConf('root.dir').'/data/user/'.$this->uid;
		/*
		mkdir($this->imageDir);
		chmod($this->imageDir,0777);
		mkdir($this->imageDir.'/img');
		mkdir($this->imageDir.'/img/300x300');
		mkdir($this->imageDir.'/img/200x200');
		mkdir($this->imageDir.'/img/150x150');
		mkdir($this->imageDir.'/img/100x100');
		mkdir($this->imageDir.'/img/75x75');
		mkdir($this->imageDir.'/img/50x50');
		mkdir($this->imageDir.'/img/25x25');
		*/
	}


	function formApply( $Req )
	{
		if(!$Req->isEmpty('photo'))
		{
			$file = $Req->get('photo.tmp_name');
			$to = $this->imageDir."/img/300x300/user.jpg";
			`convert -resize 300x300 $file $to`;
			$to = $this->imageDir."/img/200x200/user.jpg";
			`convert -resize 200x200 $file $to`;
			$to = $this->imageDir."/img/150x150/user.jpg";
			`convert -resize 150x150 $file $to`;
			$to = $this->imageDir."/img/100x100/user.jpg";
			`convert -resize 100x100 $file $to`;
			$to = $this->imageDir."/img/75x75/user.jpg";
			`convert -resize 75x75 $file $to`;
			$to = $this->imageDir."/img/50x50/user.jpg";
			`convert -resize 50x50 $file $to`;
			$to = $this->imageDir."/img/25x25/user.jpg";
			`convert -resize 25x25 $file $to`;
			
			$Req->set('photo', 'user.jpg');
		}
		return $this->Ctrl->resultFactory(
			array(
				'status'  => 'error',
				'request' => $Req->get()
			)
		);
	}

	function snipForm( )
	{
		$bind = $this->templateSnipForm(
			"form.profile.photo.conf","profile.photo.form","profile.photo.apply"
		);
		$e = $bind['form']->getInput('photo');
		$e->setValue('user.jpg');
		$e->setUrl( $this->Ctrl->getConf('site.url').'/app/profile.photo/user/'.$this->uid.'/show');
		return $bind;
	}

	function run( )
	{
		if( !$this->Request->isEmpty('user') ){
			$id  = $this->Request->get('user');
			$name = $this->Request->get('show');
			$size = $this->Request->getOr('size','150');
			$image = $this->Ctrl->getConf('root.dir').'/data/user/'.$id.'/img/'.$size.'x'.$size.'/'.$name;
			header('content-type: image/jpeg;');
			echo file_get_contents($image);
			die();
		}
		return parent::run( );
	}
}
?>
