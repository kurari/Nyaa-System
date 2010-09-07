<?php
/**
 * Twitter Application
 * ----
 *
 */
require_once dirname(__FILE__).'/conf.class.php';
require_once 'form/form.class.php';
require_once 'third/qauth/twitteroauth/twitteroauth.php';

class TwitterApp extends NyaaControllerApp
{

	function init( )
	{
		$this->db         = NyaaDB::factory(sprintf('sqlite:localhost:/%s/data/twitter.db', $this->Ctrl->getConf('root.dir')));
		$this->uid        = $this->Ctrl->getSession()->get('user.id');
		$this->con_key    = $this->Ctrl->getConf('twitter.key');
		$this->con_secret = $this->Ctrl->getConf('twitter.secret');
	}

	/**
	 * @param url http://localhost/'.$this->Ctrl->getConf('site.url').'/app/conf.twitter'
	 */
	function newOAuthToken( $url = '.' )
	{
		$connection = new TwitterOAuth($this->con_key, $this->con_secret);
		$request_token = $connection->getRequestToken( $url );
		$token = $request_token['oauth_token'];
		$secret = $request_token['oauth_token_secret'];
		return array($token, $secret);
	}

	/**
	 * Save OAuth Acess Tokens
	 */
	function saveOAuth( $id, $token, $secret )
	{
		$sth = $this->db->prepare('DELETE FROM twitter WHERE uid=:uid');
		$sth->bindParam('uid', $id);
		$sth->execute();
		$sth = $this->db->prepare('INSERT INTO twitter (uid,token,token_secret) VALUES (:uid,:token,:token_secret);');
		$sth->bindParam('token', $token);
		$sth->bindParam('token_secret', $secret);
		$sth->bindParam('uid', $id);
		$sth->execute( );
	}

	/**
	 * restore OAuth Access Token
	 */
	function getOAuthToken( $id )
	{
		$sth = $this->db->prepare('SELECT * FROM twitter WHERE uid=:uid');
		$sth->bindParam('uid', $id);
		$sth->execute();
		$res = $sth->fetch( );
		return empty($res) ? false: $res;
	}

	/**
	 * Delete OAuth Access Token
	 */
	function deleteOAuth( $id )
	{
		$sth = $this->db->prepare('DELETE FROM twitter WHERE uid=:uid');
		$sth->bindParam('uid', $id);
		$sth->execute();
		$res = $sth->fetch( );
		return empty($res) ? false: $res;
	}

	/**
	 * url http://api.twitter.com/1/statuses/home_timeline.xml
	 */
	function twitterApi( $id, $url, $method="GET",$param = array() )
	{
		$token = $this->getOAuthToken( $id );
		$conKey  = $token['con_key'];
		$conScr  = $token['con_secret'];
		$conKey  = $this->Ctrl->getConf('twitter.key');
		$conScr  = $this->Ctrl->getConf('twitter.secret');
		$acToken = $token['token'];
		$acScr   = $token['token_secret'];

		$oa = new TwitterOAuth( $conKey, $conScr, $acToken, $acScr );
		return $oa->OAuthRequest($url,$method, $param);
	}

	function tweet( $id, $message )
	{
		$this->twitterApi( $id, 'http://api.twitter.com/1/statuses/update.xml', 'POST', array('status'=>$message));
	}

	function verifieOAuthToken( $verifire, $con_key, $con_secret )
	{
		$connection = new TwitterOAuth($this->con_key, $this->con_secret, $con_key, $con_secret);
		$access_token = $connection->getAccessToken($verifire);
		return $access_token;
	}


	function run( )
	{
		// For Twitter Call Back
		// Twitter will replay
		//oauth_token=GXRejSGTlj7lEXXRUV7azpsHIRfpIcRaDVXL304A&oauth_verifier=uAF0gARsbRbrLRjIyYSQE3UoRkdcZMILSWIeQrbbwQ
		if( !$this->Request->isEmpty('oauth_verifier') )
		{
			$token        = $this->Request->get('oauth_token');
			$token_secret = $this->Request->get('oauth_verifier');
			$access_token = $this->verifieOAuthToken( $token_secret, $_SESSION['con_key'], $_SESSION['con_secret']);
			$this->saveOAuth( $this->uid, $access_token['oauth_token'], $access_token['oauth_token_secret']);
		}

		if( !$this->Request->isEmpty('destroy') )
		{
			$this->deleteOAuth( $this->uid );
		}

		$pare = $this->getOAuthToken( $this->uid );
		if($pare == false)
		{
			list($t,$s) = $this->newOAuthToken( );
			$_SESSION['con_key'] = $t;
			$_SESSION['con_secret'] = $s;
			$title = "連携する";
			$url = $this->Ctrl->getConf('twitter.authurl').$t;
		}else{
			$title = "解除する";
			$url = $this->Ctrl->getConf('site.url').'/app/conf.twitter/destroy/true';
		}

		$Tpl = $this->getTemplater( );
		$Tpl->set('title', $title);
		$Tpl->set('url', $url);
		return $Tpl->fetch('conf.html');
	}
}
?>
