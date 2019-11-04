<?PHP
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB;
use MarwaDB\Exceptions\ArrayNotFoundException;
use MarwaDB\Exceptions\NotFoundException;

class InsertSql
{
	/**
	 * var string
	 * */
	var $insertSql=null;

	/**
	 * var array
	 * */
	var $data=[];

	/**
	 * var integer
	 * */
	var $lastId=null;
	/**
	 * Insert Query Format
	 * var String
	 * */
	var	$insertQuery = 'INSERT INTO %s (%s) VALUES ( %s )';

	/**
	 * var \MarwaDB\DB
	 * */
	var $db=null;

	/**
	 * table name
	 * var string
	 * */
	var $table=null;

	/**
	 * reuslt output
	 * var boolean
	 * */
	 var $result=false;

	/**
	 * function __construct
	 * @param   \MarwaDB\DB $db description
	 * @param  Array $data description
	 * @return  Boolean description
	 * */

	public function __construct($db,$table,$data)
	{
    //check if array
    if(!is_array($data))
    {
      throw new ArrayNotFoundException("Parameter must be Array");
    }

		//assign database object
		$this->db = $db;
		//set table name
		$this->table=$table;
		//data to insert
		$this->data = $data;
	}

	/**
	 * function to insertSql
	 * @param  Array $paramname description
	 * @return  Boolean description
	 * */
	public function save()
	{
			if(!$this->is_multi())
			{
				$sql = $this->buildInsertSql($this->data);
				return $this->execute($sql);
			}
			else
			{
				//try to catch the error
				try
				{
					//begin transaction before insert
					$this->db->beginTrans();
					//loop through data
					foreach ($this->data as $key => $value)
					{
						//build sql
						$insertSql = $this->buildInsertSql($value);
						//save data
						$this->execute($insertSql);
					}
					//commit the data
					$this->db->commit();
					//return true if success
					$this->result = true;
				}
				catch (Exception $e)
				{
						$this->db->rollback();
						$this->result = false;
	 			}
			}

			return $this->result;

	}

	/**
	 * function to execute sql on PDO
	 * @param  String $sql description
	 * @return  result description
	 * */
	protected function execute($sql)
	{
			if(!is_null($this->db) && !is_null($sql))
			{
				$result = $this->db->insert($sql[0],$sql[1]);
				$this->lastId = $this->db->getPdo()->lastInsertId();
				return $result;
			}
			else
				return false;
	}
	/**
	 * function to build Insert Sql
	 * @param  Array $paramname description
	 * @return  String description
	 * */
	public function buildInsertSql(array $data)
	{
  		$columns=[];
  		$value =[];
  		$placeHolders=[];
  		$sqlArr=[];
		//loop through array
		foreach($data as $col => $val)
		{
				//inject column name
				array_push($columns,$col);
				//inject column value
				array_push($value, $val);
				//inject place holder
				array_push($placeHolders, "?");
		}

		//implode column with comma(,)
		$colSql = implode(",",$columns);
		//implode placeholder with comma(,)
		$placeHolders = implode(",",$placeHolders);
		//replace sql place holder
		$sqlArr[0] = sprintf($this->insertQuery,$this->table,$colSql,$placeHolders);
		$sqlArr[1] = $value;
		return $sqlArr;
	}

	/**
	 * function to get Last Inserted Id
	 * */

	public function getLastId()
	{
			return $this->lastId;
	}
	/**
	 * function to check if array is multi-dimensional or not
	 * @param  Array $a description
	 * @return  Boolean description
	 * */
	public function is_multi() {
    	$rv = array_filter($this->data,'is_array');
    	if(count($rv)>0) return true;
    	return false;
	}


}
