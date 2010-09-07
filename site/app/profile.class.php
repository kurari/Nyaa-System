<?php
class ProfileApp extends NyaaControllerApp
{
	protected $view = 'profile.html';

	function init( )
	{
		$this->db = $this->Ctrl->get('db.system');
	
	}	
	function getAll( )
	{
		return $this->db->query('SELECT * FROM profile;');
	}


	function formApply( $Req )
	{
		$values = array();
		foreach($Req->get( ) as $k=>$v) {
			if(preg_match('/(.*)_public$/',$k,$m)) {
				$value = $Req->get($m[1]);
				if($m[1] == "birthday")
					$value = $value['year'].'-'.$value['month'].'-'.$value['day'];
				$values[] = array(
					'userid'=>$this->Ctrl->getSession()->get('user.id'),
					'name'=>$m[1],
					'pub'=>$v,
					'value'=>$value
				);
			}
		}
		$this->db->begin();
		$sql = 'DELETE FROM profile WHERE userid=:userid;';
		$sth = $this->db->prepare($sql);
		$sth->execute(array('userid'=>$this->Ctrl->getSession()->get('user.id')));
		$sql = 'INSERT INTO profile (userid,name,value,pub) VALUES (:userid,:name,:value,:pub);';
		$sth = $this->db->prepare($sql);
		foreach($values as $v) $sth->execute( $v );
		$this->db->commit();

		return $this->Ctrl->resultFactory(
			array(
				'status'  => 'error',
				'request' => $Req->get()
			)
		);
	}

	function getProfile( $id )
	{
		$values = array();
		$sql    = 'SELECT * FROM profile WHERE userid = :userid;';
		$sth    = $this->db->prepare($sql);
		$sth->execute(array('userid'=>$id));
		foreach($sth as $v){
			$values[$v["name"]] = $v["value"];
			$values[$v["name"]."_public"] = $v["pub"];
			if($v["name"] == "birthday"){
				list($y,$m,$d) = explode("-",$v["value"]);
				$values[$v["name"]] = array('year'=>$y,'month'=>$m,'day'=>$d);
			}
		}
		return $values;
	}



	function snipForm( )
	{
		$bind = $this->templateSnipForm(
			"form.profile.conf","profile.form","profile.apply"
		);

		if(!isset($bind['request'])){
			$values = $this->getProfile($this->Ctrl->getSession()->get('user.id'));
			$bind['form']->setValues($values);
			$bind['request'] = $values;
		}


		foreach($bind['form']->getInputs() as $v){
			$name = $v->getProp('name').'_public';
			$c = NyaaFormInput::factory(
				array( 
					'name'    => $name,
					'type'    => 'select',
					'blank'   => false,
					'options' => array(
						'public'         => '公開',
						'inpublic'       => '非公開',
						'friends'        => '友達のみ',
						'friendsfriends' => '友達の友達のみ'
					)
				)
			);
			$c->setValue(isset($bind['request'][$name]) ?  $bind['request'][$name]: false);
			$v->ask = $c;
		}

		return $bind;
	}

	function snipMenus( )
	{
		$menu = array();
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/profile',
			'title' => '基本'
		);
		$menu[] = array(
			'url' => $this->Ctrl->getConf('site.url').'/app/profile.photo',
			'title' => '画像'
		);
		return array('menus'=>$menu);
	}

}
?>
