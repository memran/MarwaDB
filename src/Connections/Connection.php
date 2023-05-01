<?php
	
	namespace MarwaDB\Connections;
	
	use MarwaDB\Connections\Exceptions\ConnectionException;
	use MarwaDB\Connections\Exceptions\InvalidException;
	use MarwaDB\Connections\Exceptions\NotFoundException;
	use MarwaDB\Connections\Interfaces\ConnectionInterface;
	use MarwaDB\Exceptions\InvalidArgumentException;
	use MarwaDB\SqlTrait;
	use MarwaDB\Util;
	use PDO;
	use PDOException;
	
	class Connection implements ConnectionInterface {
		
		use SqlTrait;
		
		/**
		 * @var Connection
		 */
		protected static $_instance;
		/**
		 * @var null|PDO
		 */
		protected $pdo=null;
		/**
		 * @var string
		 */
		protected $mode = 'array';
		/**
		 * @var array
		 */
		protected $fetchMode = [
			'array' => PDO::FETCH_ASSOC,
			'object' => PDO::FETCH_OBJ
		];
		/**
		 * @var string
		 */
		protected $default;
		/**
		 * @var array
		 */
		protected $_config;
		/**
		 * var int
		 */
		protected $numRows = false;
		/**
		 * @var bool
		 */
		protected $logging = false;
		
		/**
		 * @var array
		 */
		protected $sqlQueryLog = [];
		/**
		 * The default PDO connection options.
		 *
		 * @var array
		 */
		protected $options = [
			\PDO::ATTR_CASE => PDO::CASE_NATURAL,
			\PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			\PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
			\PDO::ATTR_STRINGIFY_FETCHES => false,
			\PDO::ATTR_EMULATE_PREPARES => false,
			\PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		];
		/**
		 * @var ConnectionLocator
		 */
		protected $locator;
		
		/**
		 * Connection constructor.
		 *
		 * @param array $dbConfig
		 * @throws InvalidException
		 * @throws NotFoundException
		 */
		private function __construct( array $dbConfig )
		{
			//check PDO driver is available
			if ( !defined('PDO::ATTR_DRIVER_NAME') )
			{
				throw new NotFoundException('PDO extension is not loaded');
			}
			$this->setDatabaseConfig($dbConfig);
		}
		
		/**
		 * @param array $config
		 * @throws InvalidException
		 */
		protected function setDatabaseConfig( array $config ) : void
		{
			//check Database config exists
			if ( empty($config) )
			{
				throw new InvalidException("Invalid Database Connection Paramters");
			}
			
			$this->locator = new ConnectionLocator();
			
			foreach ( $config as $name => $connection )
			{
				$this->locator->addConnection($name, $connection);
			}
		}
		
		/**
		 * @param array $config
		 * @return ConnectionInterface
		 * @throws InvalidArgumentException
		 * @throws InvalidException
		 * @throws NotFoundException
		 */
		public static function getInstance( array $config ) : ConnectionInterface
		{
			if ( empty($config) || !is_array($config) )
			{
				throw new InvalidArgumentException("Database configuration is not provided");
				
			}
			if ( !isset(static::$_instance) )
			{
				static::$_instance = new Connection($config);
			}
			
			return static::$_instance;
		}
		
		/**
		 * @param string $name
		 * @return $this|ConnectionInterface
		 * @throws InvalidException
		 */
		public function connection( string $name ) : ConnectionInterface
		{
			if ( empty($name) )
			{
				throw new InvalidException("Invalid Connection name");
			}
			
			$this->default = $name;
			
			return $this;
		}
		
		/**
		 * @return bool
		 * @throws ConnectionException
		 */
		public function reconnect()
		{
			$this->pdo = null;
			
			return $this->connect();
		}
		
		/**
		 * @return bool
		 * @throws ConnectionException
		 */
		public function connect() : bool
		{
			
			//try to connect PDO
			try
			{
				$this->pdo = new PDO(
					$this->getDsn(),
					$this->getUsername(),
					$this->getPassword(),
					$this->getOptions()
				);
				
				return true;
			} catch ( PDOException $e )
			{
				throw new ConnectionException("Failed to Connect Database");
			}
		}
		
		/**
		 * @return string
		 */
		protected function getDsn() : string
		{
			$format = "%s:host=%s;dbname=%s";
			$dsnStr = sprintf(
				$format,
				$this->getDriver(),
				$this->getHost(),
				$this->getDB()
			);
			
			if ( $this->getDriver() === "mysql" )
			{
				$dsnStr .= ';charset=' . $this->getCharSet();
			}
			
			return $dsnStr;
		}
		
		/**
		 * @return string
		 */
		public function getDriver() : string
		{
			return strtolower($this->getConnection()['driver']);
		}
		
		
		/**
		 * @return array
		 */
		public function getConnection() : array
		{
			return $this->locator->getConnection($this->getDefaultConnectionName());
		}
		
		/**
		 * @return string|null
		 */
		protected function getDefaultConnectionName() : ?string
		{
			return $this->default;
		}
		
		/**
		 * @return string
		 */
		public function getHost() : string
		{
			return $this->getConnection()['host'];
		}
		
		/**
		 * @return string
		 */
		public function getDB() : string
		{
			return $this->getConnection()['database'];
		}
		
		/**
		 * @return string
		 */
		public function getCharSet() : string
		{
			return $this->getConnection()['charset'];
		}
		
		/**
		 * @return string
		 */
		public function getUsername() : string
		{
			return $this->getConnection()['username'];
		}
		
		/**
		 * @return string
		 */
		public function getPassword() : string
		{
			if ( empty($this->getConnection()['password']) || is_null($this->getConnection()['password']) )
			{
				return "";
			}
			
			return $this->getConnection()['password'];
		}
		
		/**
		 * @return array
		 */
		public function getOptions()
		{
			if ( array_key_exists('options', $this->getConnection()) )
			{
				return array_unique(array_merge($this->options, $this->getConnection()['options']));
			}
		}
		
		/**
		 * @return $this
		 */
		public function enableLog()
		{
			$this->logging = true;
			
			return $this;
		}
		
		/**
		 * @return array
		 */
		public function getQueryLog() : array
		{
			return $this->sqlQueryLog;
		}
		
		/**
		 * @param string $sqlQuery
		 * @param array $bindParam
		 * @return array|int
		 * @throws InvalidException
		 */
		public function query( string $sqlQuery, array $bindParam = [] )
		{
			if ( empty($sqlQuery) )
			{
				throw new InvalidException("Query string is empty");
			}
			
			try
			{
				return $this->executeQuery($this->removeWhiteSpace($sqlQuery), $bindParam);
			} catch ( PDOException $e )
			{
				throw new PDOException($e->getMessage(), (int) $e->getCode());
			}
		}
		
		/**
		 * @param string $sqlQuery
		 * @param array $bindParam
		 * @return array|bool
		 * @throws ConnectionException
		 */
		protected function executeQuery( $sqlQuery, $bindParam )
		{
			/**
			 *  if Sql log is enable then store sql and binding to the array for further usage
			 */
			if ( $this->logging )
			{
				array_push($this->sqlQueryLog, ['sql' => $sqlQuery, 'binding' => $bindParam]);
			}
			/**
			 *  Executing sql query
			 */
			$stmt = $this->getPdo()->prepare($sqlQuery);
			$res = $stmt->execute($bindParam);
			
			/**
			 * Read Affected rows
			 */
			$this->setAffectedRows($stmt->rowCount());
			
			/**
			 * check query is startwith SELECT
			 */
			if ( $this->detectSelectSql($sqlQuery) )
			{
				return $stmt->fetchAll($this->getFetchMode());
			}
			
			return $res;
		}
		
		/**
		 * @return PDO
		 * @throws ConnectionException
		 */
		protected function getPdo()
		{
			/**
			 * If connection exists then return existing connection
			 */
			if ( isset($this->pdo) )
			{
				return $this->pdo;
			}
			$this->connect();
			
			return $this->pdo;
		}
		
		/**
		 * @param int $rows
		 */
		protected function setAffectedRows( int $rows ) : void
		{
			$this->numRows = $rows;
		}
		
		/**
		 * @param string $sql
		 * @return bool|void
		 */
		protected function detectSelectSql( string $sql )
		{
			return Util::startsWith(strtolower($sql), 'select');
		}
		
		/**
		 * @return int
		 */
		public function getFetchMode() : int
		{
			return $this->fetchMode[ $this->mode ];
		}
		
		/**
		 * @param string $type
		 * @return $this|ConnectionInterface
		 */
		public function setFetchMode( string $type ) : ConnectionInterface
		{
			$this->mode = strtolower($type);
			
			return $this;
		}
		
		/**
		 * @return string
		 * @throws ConnectionException
		 */
		public function getLastId() : string
		{
			return $this->getPdo()->lastInsertId();
		}
		
		/**
		 * @return int
		 */
		public function getAffectedRows() : int
		{
			return $this->numRows;
		}
		
		/**
		 * @return string
		 */
		public function status() : string
		{
			if ( !is_null($this->pdo) )
			{
				return $this->pdo->getAttribute(constant("PDO::ATTR_SERVER_INFO"));
			}
			
			return '';
		}
		
		/**
		 * @throws ConnectionException
		 */
		public function beginTrans()
		{
			try {
				return $this->getPdo()->beginTransaction();
			}
			catch(Exceptions $e)
			{
				throw new ConnectionException($e);
			}
		}
		
		/**
		 * @throws ConnectionException
		 */
		public function rollBackTrans()
		{
			try{
				return $this->getPdo()->rollback();
			}
			catch(Exceptions $e)
			{
				throw new ConnectionException($e);
			}
		}
		
		/**
		 * @throws ConnectionException
		 */
		public function commitTrans()
		{
			try{
				return $this->getPdo()->commit();
			}
				catch(Exceptions $e)
			{
				throw new ConnectionException($e);
			}
		}
		
		/**
		 *
		 */
		protected function __destroy()
		{
			$this->pdo = null;
		}
	}
