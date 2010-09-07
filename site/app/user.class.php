<?php
/**
 * User Application
 * ----
 *
 * User Application, which has login logout getUser also
 *
 */
class UserApp extends NyaaControllerApp
{

	function init( )
	{
		$this->db = $this->Ctrl->get('db.system');
	}

	/**
	 * For validater
	 */
	function validateLogin( $keys, $datas, &$result, &$validater )
	{
		$data = array();
		foreach($keys as $k)
			$data[$k] = $datas[$k];
		if( !$this->login( $data ) )
		{
			$result->addInvalid("login");
		}
	}

	/**
	 * Login
	 */
	function login( $data )
	{
		$sth = $this->db->prepare(
			'SELECT id,familyName,firstName FROM user WHERE email=:email AND password=:password;'
		);
		$sth->execute($data);
		$data = $sth->fetch();
		if(false == $data)
			return false;

		$session =& $this->Ctrl->getSession( );
		$session->set('user', $data);

		return true;
	}

	/**
	 * Logout
	 */
	function logout( )
	{
		$this->Ctrl->getSession( )->set('user', array());
		setcookie('userid','',-1,'/');
	}

	/**
	 * Regist New User
	 */
	function createNewUser( $data )
	{
		$sth = $this->db->prepare('
			INSERT INTO user 
			(familyName,firstName,email,password,gender,birthday)
			VALUES
			(:familyName,:firstName,:email,:password,:gender,:birthday)
			');
		$sth->execute($data);
		return $sth->getLastId( );
	}

	/**
	 * Get User By Id
	 */
	function getUser($id)
	{
		$sth = $this->db->prepare(
			'SELECT id,familyName,firstName FROM user WHERE id=:id;'
		);
		$sth->execute(array('id'=>$id));
		$data = $sth->fetch();
		return $data;
	}

	/**
	 * search user
	 */
	function search( )
	{
		$sth = $this->db->prepare(
			'SELECT id,familyName,firstName,birthday FROM user;'
		);
		$sth->execute( );
		$data = $sth->fetchAll();
		return $data;
	}

	function snipProfile( $opt )
	{
		$data = $this->getUser( $opt['id'] );
		return $data;
	}

}
?>
