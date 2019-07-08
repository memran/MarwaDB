<?php
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB;

use PDO;
use MarwaDB\Exceptions\NotFoundException;
use MarwaDB\ConnectionInterface;

class Connection implements ConnectionInterface
{

	/**
	 *  connection object
	 * */
	protected $pdo=null;

	/**
	 * var default connection name
	 * */
	var $defaultConn=null;

	/**
	 * Array
	 * */
	protected $dbConfig =[];

	/**
	 * var int
	 * */
	protected $numRows=false;

	/**
	 * The default PDO connection options.
	 *
	 * @var array
	 */
	protected $options = array(
	        \PDO::ATTR_CASE => PDO::CASE_NATURAL,
	        \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	        \PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
	        \PDO::ATTR_STRINGIFY_FETCHES => false,
	        \PDO::ATTR_EMULATE_PREPARES => false,
	        \PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
	);

	/**
	 * Function constructor
	 * @param  $dbConfig description
	 * */
	public function __construct($dbConfig)
	{
		//check PDO driver is available
		if (!defined('PDO::ATTR_DRIVER_NAME'))
		{
				throw new NotFoundException('PDO extension is not loaded');
		}

		//check Database config exists
		if(is_null($dbConfig))
		{
			throw new ArrayNotFoundException("Database Configuration Array");
		}
		$this->validateAndBuild($dbConfig);

	}

	/**
	 * ValidateAndBuild Function to validate database configuration and setup connection
	 *@param   $config Array description
	 * */
	public function validateAndBuild($config)
	{
			foreach($config as $k => $v)
			{
					$this->setConnection($k,$v);
			}
	}

	/**
	 * Function to Setup Connection for databsae
	 * @param   $name connection name
	 * @param   $config connection configuration array
	 * @return   void description
	 * */
	public function setConnection($name,$config)
	{
		//check if name is  null
		if(is_null($name))
		{
			throw new NotFoundException("Connection name not found");
		}
		//check if database config is array
		if(is_array($config))
		{
			$this->dbConfig[$name] = $this->__validateKey($config);
			//set default connection
			if(is_null($this->defaultConn))
			{
				$this->defaultConn = $name;
			}
		}
		else
		{
			throw new ArrayNotFoundException("Database Configuration Array not found!");
		}

	}
	/**
	 * function validation key for database
	 * @param   $dbConfig description
	 * @return  $dbConfig description
	 * */
	private function __validateKey($dbConfig)
	{
		//check mysql configuration is validate
		if(!array_key_exists('driver',$dbConfig))
		{
			throw new NotFoundException("Database Driver parameter not found");
		}
		else
		{
			 if(is_null($dbConfig['driver']) or !isset($dbConfig['driver']) )
			 {
			 		throw new NotFoundException("Database Driver parameter is not valid");
			 }

		}

		//check host is valid
		if(!array_key_exists('host',$dbConfig))
		{
			throw new NotFoundException("Database host parameter not found");
		}
		else
		{
			if(is_null($dbConfig['host']) or !isset($dbConfig['host']) )
			{
				throw new NotFoundException("Host Parameter is not valid");
			}
		}

		//check username
		if(!array_key_exists('username',$dbConfig))
		{
			throw new NotFoundException("Username Parameter not found");
		}
		else
		{
				if(is_null($dbConfig['username']) or !isset($dbConfig['username']) )
				{
					throw new NotFoundException("Username Parameter is not valid");
				}

		}
		//check password is  null
		if(!array_key_exists('password',$dbConfig))
		{
			throw new NotFoundException("Password Parameter is not valid");
		}
		else
		{
			 if(is_null($dbConfig['password']))
			 {
						throw new NotFoundException("Password Parameter is not valid");
			 }
		}
		//check database name is provided or not
		if(!array_key_exists('database',$dbConfig))
		{
			throw new NotFoundException("Database Parameter not found");
		}
		else
		{
			 if(is_null($dbConfig['database']) or !isset($dbConfig['database']) )
				{
					throw new NotFoundException("Database Parameter is not valid");
				}
		}

		$dbNewConfig['dsn'] = $this->buildDSN($dbConfig);
		$dbNewConfig['username'] = trim($dbConfig['username']);
		$dbNewConfig['password'] = trim($dbConfig['password']);
		$dbNewConfig['options'] = $this->options;
		return $dbNewConfig;
	}

	/**
	 * function buildDSN
	 * @param   $paramname description
	 * @return  String description
	 * */
	private function buildDSN($config)
	{
			$dsn = $config['driver'].":host=".$config['host'].";dbname=".$config["database"];
			if($config['driver'] === "mysql")
			{
				$dsn .= ";charset=".$config['charset'];
			}
			return $dsn;
	}
	/**
	 * function getConnection
	 * @param   $name Connection name
	 * */
	public function getConnection($name=null)
	{
		if(!is_null($name))
		{
			if(array_key_exists($name, $this->dbConfig))
			{
				$this->defaultConn = $name;
				return $this->reconnect();
			}
			else
			{
				throw new NotFoundException("Connection name not found");
			}
		}
		return $this->connect();
	}

    /**
     * function to connect Database
     * */
	public function connect()
	{
		if(!is_null($this->pdo))
		{
			return $this->pdo;
		}
		//try to connect PDO
		try
		{
			$this->pdo = new PDO($this->dbConfig[$this->defaultConn]['dsn'],$this->dbConfig[$this->defaultConn]['username'],$this->dbConfig[$this->defaultConn]['password'],$this->dbConfig[$this->defaultConn]['options']);
		}
		catch(PDOException $e)
	    {
	    	throw new PDOException($e->getMessage(), (int)$e->getCode());
	    }

		  return $this->pdo;

	}

	/**
	 * function to reconnect Database
	 * @return  PDO description
	 * */
	public function reconnect()
	{
			$this->pdo = null;
			return $this->connect();
	}

	/**
	 * function database query execution
	 * @param  $sqlQuery description
	 * @param  $bindParam
	 * */
	public function query($sqlQuery,$bindParam=[])
	{

			$sqlQuery=$this->removeWhiteSpace($sqlQuery);

			//statement prepare
			$stmt=null;
			//fetch result
			$result=null;
			//connect the database
			$this->connect();

			//check bindParam is empty
			if(empty($bindParam))
			{
				$stmt = $this->pdo->query($sqlQuery);
			}
			else //if bind param is not empty
			{
				$stmt = $this->pdo->prepare($sqlQuery);
				$stmt->execute($bindParam);
			}

			$this->numRows = $stmt->rowCount();

			//check query contains select
			$selectQuery = $this->isSelect($sqlQuery);

			//check if select query returns true
			if($selectQuery)
			{
				//check if it is multiple rows
				if($this->numRows > 1 )
				{
					return $stmt->fetchAll();
				}
				else //return if rows 1 or less
				{
					return $stmt->fetch();
				}

			}
			else
			{
				return $this->numRows;
			}
	}

	/**
	 * Function to remove Whitespace in String
	 * @param  string $paramname description
	 * @return  string description
	 * */
	private function removeWhiteSpace($text)
	{
	    $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
	    $text = preg_replace('/([\s])\1+/', ' ', $text);
	    $text = trim($text);

		return $text;
	}


	/**
	 * function to return number of rows from sql query
	 * @return  int description
	 * */
	public function rows()
	{
			return $this->numRows;
	}
	/**
	 * function to check sql query start with SELECT
	 * @param  $sql description
	 * */
	private function isSelect($sql)
	{
		$arr = explode(' ',trim($sql));
		if(strtoupper($arr[0]) === "SELECT")
		{
			return true;
		}
		else
			return false;

	}

	/**
	 * function to get Server info from PDO connection
	 * @return  String|null description
	 * */
	public function status()
	{
		if(!is_null($this->pdo))
		{
				return $this->pdo->getAttribute(constant("PDO::ATTR_SERVER_INFO"));
		}
		return null;
	}

	/**
	 * Function to close PDO Connection
	 * @return  void description
	 * */
	public function close()
	{
		//if PDO is not null
		if(!is_null($this->pdo))
		{
			//set null to $pdo
			$this->pdo = null;
		}

	}

	/**
	 * function to destruct class
	 * */
	public function __destruct()
	{
		$this->close();
	}

}
?>
