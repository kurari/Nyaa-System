<?php
/**
 * Class
 */
require_once 'templater/resource.class.php';
require_once 'templater/function.class.php';
require_once 'templater/compiler.class.php';
require_once 'templater/modifier.class.php';
require_once 'templater/templater.default.class.php';

require_once 'object/object.class.php';
require_once 'store/store.class.php';

class NyaaTemplater extends NyaaObject
{
	public $templateDir;
	public $cacheDir;
	protected $leftDelimiter;
	protected $rightDelimiter;
	protected $_store;
	public $cache = false;

	public static function factory($name="default")
	{
		$class = NyaaTemplater.ucfirst($name);
		$tpl = new $class( );
		return $tpl;
	}

	public function &getRef($name )
	{
		return $this->_store->getRef($name);
	}

	/**
	 * For Templater Vars
	 */
	function __call( $func, $args )
	{
		if(in_array($func, array('get','getRef','set','dump')))
		{
			return call_user_func_array(array($this->_store,$func), $args);
		}
		parent::__call( $func, $args);
	}

	// Setter or Getter
	function getTemplateDir( )
	{
		return $this->templateDir;
	}
	function getCacheDir( )
	{
		return $this->cacheDir;
	}


	// registering handlers
	function registerResourceHandler( $type, $object )
	{
		$this->plugin['resource'][$type] = $object;
	}
	function registerCompilerHandler( $type, $object )
	{
		$this->plugin['compiler'][$type] = $object;
	}
	function registerFunctionHandler( $type, $object )
	{
		$this->plugin['function'][$type] = $object;
	}
	function registerModifierHandler( $type, $object )
	{
		$this->plugin['modifier'][$type] = $object;
	}

	function getOpt( $text )
	{
		$opt = "";
		if( preg_match_all('/\s*([^=]+)\s*=\s*((?:[^"(\s]|"[^"]+"|\([^\)]*\))+ )\s*/xms', $text, $m ) )
		{
			$opt = array_combine( $m[1], $m[2] );
		}
		return $opt;
	}

	function getOptExported($text)
	{
		$ret = "array(";
		if( preg_match_all('/\s*([^=]+)\s*=\s*((?:[^"(\s]|"[^"]+"|\([^\)]+\))+ )\s*/xms', $text, $m ) )
		{
			$opt = array_combine( $m[1], $m[2] );
			$work = array( );
			foreach($opt as $k=>$v)
			{
				//$work[] = "'$k'=>$v";
				$work[] = "'$k'=>".$this->compileVarLine($v);
			}
			$ret.= implode(',', $work);
		}
		$ret.= ")";
		return $ret;
	}

	function getResource( $res )
	{
		$status = array( );

		if(preg_match('#(.*?)://(.*)#xms', $res, $m))
		{
			$type = $m[1];
			$path = $m[2];
		}else{
			$type = 'file';
			$path = $res;
		}

		if( !isset($this->plugin['resource'][$type]) )
		{
			// don't have resource type
		}

		$resHandler = $this->plugin['resource'][$type];

		$status['TYPE'] = $type;
		$status['PATH'] = $path;

		if( false === $resHandler->hasCache($path,$this) )
		{
			$status['CACHED'] = "NO";
			$text = $resHandler->get($path, $this);
			$code = $this->compile($text);
			$resHandler->writeCache($path, $code, $this);
		}
		else
		{
			$status['CACHED'] = "YES";
			$code = $resHandler->getCache($path,$this);
		}
		return $code;
	}

	function display( $res )
	{
		$status['code'] = $code = $this->getResource($res);
		$this->set('status', $status);

		 //highlight_string( $code);
		eval('?>'.$code);
	}

	function fetch( $res )
	{
		ob_start( );
		$this->display( $res );
		return ob_get_clean( );
	}

	function compile( $text )
	{
		$lt    = $this->leftDelimiter;
		$rt    = $this->rightDelimiter;
		$elt   = preg_quote($lt);
		$ert   = preg_quote($rt);
		$code  = "";
		while( preg_match('/(?P<prev>.*?)'.$elt.'(?P<content>.*?)'.$ert.'(?P<text>.*)/msx', $text, $m) )
		{
			$code.= $m['prev'];
			$text = $m['text'];

			if($m['content'][0] == '$')
			{
				$code.= '<?php echo ';
				$code.= $this->compileVar( substr(trim($m['content']),1) );
				$code.= '; ?>';
				continue;
			}

			$info = explode(' ', trim($m['content']),2);
			$name = $info[0];
			$opt  = isset($info[1]) ? $info[1]: "";


			if(isset($this->plugin['compiler'][$name]))
			{
				$compiler = $this->plugin['compiler'][$name];

				if( true === $compiler->isBlock( $name, $opt) )
				{
					$target   = "$lt/$name$rt";
					$pos      = strpos( $text, $target );
					$subText  = substr($text,0,$pos);
					if(  0 < $start = substr_count($subText,$lt.$name) )
					{
						while( false !== $next = strpos( $text, $target, $pos + strlen($target) ))
						{
							$subText  = substr($text,0,$next);
							if( 1 > substr_count($subText,$lt.$name) - substr_count($subText,$target) )
							{
								$pos = $next;
								break;
							}
							$pos = $next;
						}
					}
					$code    .= $compiler->compile( $name, $opt, $subText , $this );
					$text     = substr($text,$pos+strlen($target));

				}
				else
				{
					$code.= $compiler->compile( $name, $opt, "", $this );
				}
			}elseif(isset($this->plugin['function'][$name])){
				$code.= sprintf('<?php $this->doFunction("%s",%s,$this); ?>', $name, $this->getOptExported($opt));
			}else{
				$this->warning('tpl keyword %s is not defined', $name);
			}
		}
		$code .= $text;

		return $code;
	}

	function compileVar( $text )
	{
		$aWork = explode( '|', $text );
		$text = array_shift($aWork);
		$var = preg_replace('/^([^-]+)/', '$this->get("\1")', trim($text),1);
		while( !empty($aWork) )
		{
			$filter = array_shift($aWork);
			$var = sprintf('$this->doModifier("%s", %s, $this)', trim($filter), $var);
		}
		return $var;
	}

	function compileVarLine( $text )
	{
		return preg_replace('/\$((?:[^=-\s\(\)]|\([^\)]+\))+)/xe','$this->compileVar("\1")', $text );
	}


	function doFunction( $name, $option, $templater )
	{
		if(!isset($this->plugin['function'][$name]))
		{
			$this->warning('tpl function %s is not defined', $name);
			return false;
		}
		$function = $this->plugin['function'][$name];
		return $function->execute($name, $option, $templater);
	}

	function doModifier( $name, $text, $templater )
	{
		if(!isset($this->plugin['modifier'][$name]))
		{
			$this->warning('var modifier %s is not defined', $name);
			return false;
		}
		$modifier = $this->plugin['modifier'][$name];
		return $modifier->execute($name, $text, $templater);
	}
}

?>
