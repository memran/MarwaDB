<?PHP
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/
namespace MarwaDB;

use Exception;
use MarwaDB\WhereSqlTrait;
use MarwaDB\Exceptions\ArrayNotFoundException;

class SelectSql
{
	use WhereSqlTrait;

	/**
	 * [$sql_query description]
	 * @var string
	 */
	var $sql_query=NULL;
	/**
	 * var string table name
	 * */
	var $table;
	/**
	 * var object
	 * */
	var $db=NULL;
	/**
	 * var string
	 * */
	var $offset=NULL;
	/**
	 * var array
	 * */
	var	$selectQuery = [];
	/**
	 * [$selectFormat description]
	 * @var string
	 */
	var $selectFormat = 'SELECT %s FROM %s';
	/**
	 * [$distinctFormat description]
	 * @var string
	 */
	var $distinctFormat = 'SELECT DISTINCT %s FROM %s';
	/**
	 * [$whereSql description]
	 * @var null
	 */
	var $whereSql=NULL;  //where sql
	/**
	 * [$whereOrSql description] where OR sql
	 * @var null
	 */
	var $whereOrSql=NULL;
	/**
	 * [$whereAndSql description] where AND sql
	 * @var null
	 */
	var $whereAndSql=NULL;
	/**
	 * [$limitSql description] limit sql
	 * @var null
	 */
	var $limitSql= NULL;
	/**
	 * [$orderSql description] Order By sql
	 * @var null
	 */
	var $orderSql = NULL;
	/**
	 * [$groupSql description] GROUP BY sql
	 * @var null
	 */
	var $groupSql = NULL;
	/**
	 * [$havingSql description] Having SQL
	 * @var null
	 */
	var $havingSql = NULL;
	/**
	 * [$joinSql description]
	 * @var null
	 */
	var $joinSql = NULL;

	/**
	 * function __construct
	 * @param   MarwaDB\DB $db description
	 * @param   string $table description
	 * @param Array $columns description
	 * */
	public function __construct($db,$table,$columns)
	{
		//assign database
		$this->db=$db;
		//assign table name
		$this->table = $table;

		//array for columns
		//$colSql=[];
		if(is_array($columns))
		{
			//loop through array
			foreach($columns as $k => $v)
			{
				$tabSql = "";
        if (strpos($v, '.') !== false) {
        	$tabSql = $this->table.".".$v;
        }
        else //add the table name and push to arary
        {
          $tabSql =$v;
        }
				array_push($this->selectQuery,$tabSql);
			}
		}
		else
		{
				throw new ArrayNotFoundException("Columns is not array format");
		}

	}

	/**
	 * function for Select
	 * @param  string $columns description
	 * @return  $this description
	 * */
	public function addSelect($columns)
	{
		if(!is_null($this->selectQuery))
		{
			//check if string contains "as" then donot add table name
      if (strpos($columns, '.') !== false) {
        array_push($this->selectQuery,$columns);
      }
      else //add the table name and push to arary
      {
        array_push($this->selectQuery,$this->table.".".$columns);
      }

		}

		return $this;
	}

	/**
	 * function for Sql Distinct
	 * @return  this description
	 * */
	public function distinct()
	{
		$this->selectFormat = $this->distinctFormat;
		return $this;
	}

	/**
	 * function to get Sql as string without execute
	 * @return  string description
	 * */
	public function sqlString()
	{
		//define variable for local sql string
		$selectColumn=NULL;

		//if select query is not empty
		if(!empty($this->selectQuery))
		{
			if(is_array($this->selectQuery))
			{
				$selectColumn = implode(',',$this->selectQuery);
			}
			else
			{
				$selectColumn = $this->selectQuery;
			}
		}
		else
		{
			$selectColumn = "*";
		}
		//build sql query
		 $this->sql_query = sprintf($this->selectFormat,$selectColumn,$this->table);

		 //check join/leftjoin/rightjoin is null or not
		 if(!is_null($this->joinSql))
		 {
	 		$this->sql_query .= $this->joinSql;
		 }

		 //check whereSql
		 if(!is_null($this->whereSql))
		 {
      	//concat wheresql to the main sql
      	$this->sql_query .= $this->whereSql;

  		 	//check if not null whereOrSql
  		 	if(!is_null($this->whereOrSql))
  			{
  				$this->sql_query .= $this->whereOrSql;
  			}
  			//check if not null whereAndSql
  			if(!is_null($this->whereAndSql))
  			{
  			    $this->sql_query .= $this->whereAndSql;
  			}
	    }

		 //checking if not null havingSql
		 if(!is_null($this->havingSql))
		 {
		 	$this->sql_query .= $this->havingSql;
		 }
		  //check Group Sql
		 if(!is_null($this->groupSql))
		 {
		 	$this->sql_query .= $this->groupSql;
		 }

		 //check Order Sql
		 if(!is_null($this->orderSql))
		 {
	 	   $this->sql_query .= $this->orderSql;
		 }

		 //check limitSql
		 if(!is_null($this->limitSql))
		 {
		 	$this->sql_query .= $this->limitSql;
		 }
		 //check offset
		 if(!is_null($this->offset))
		 {
		 	$this->sql_query .= $this->offset;
		 }
		 return $this->sql_query;

	}

	/**
	 * function get will return all data form table
	 * @return  Array description
	 * */
	public function get()
	{
		//build sql query
		$this->sqlString();
	    //if Database object is not null then execute query
		if(!is_null($this->db))
		{
  			return $this->db->rawQuery($this->sql_query,$this->placeHolders);
		}
		else
		{
			throw new Exception('Database connection not found!');
		}

	}

	/**
	 * function for orderByRaw
	 * @param  string $columns description
	 * @return  $this description
	 * */
	public function orderByRaw(string $columns)
	{
		//check if columns is null
		if(is_null($columns))
		{
			throw new Exception("Order by parameter is null");
		}
    $this->orderSql = " ORDER BY {$columns}";
    //$whereFormat=' ORDER BY ?';
		//$this->orderSql = sprintf($whereFormat,$columns);
		return $this;
	}

	/**
	 * function for havingRaw
	 * @param  string $columns description
	 * @return  $this description
	 * */
  public function havingRaw(string $columns="*")
	{
		//check if columns is null
		if(!is_string($columns))
		{
			throw new Exception("Parameter must be string");
		}
		$this->havingSql = sprintf(' HAVING %s',$columns);
		return $this;
	}

	/**
	 * function for orderby sql statement
	 * @param   $colName description
	 * @param   $sortBy description
	 * */
	public function orderBy(string $colName,$sortBy='ASC')
	{
		//check if colName is null
		if(!is_string($colName))
		{
			throw new Exception("Orderby column name must be string");
		}

		$sortArray=['ASC','DESC','RAND'];

		if(!in_array($sortBy,$sortArray))
		{
			throw new Exception("Sort By key is not valid");
		}
		//$colSql ='';
	    if (strpos($colName, '.') !== false)
	    {
	    	$this->orderByRaw("{$colName} {$sortBy}");
	    }
	    else //add the table name
	    {
	      $this->orderByRaw($this->table.".{$colName} {$sortBy}");
	    }
		//$this->orderByRaw($colSql);
		return $this;
	}

	/**
	 * function for groupBy
	 * @param  string $columns description
	 * @return  $this description
	 * */
	public function groupBy(string $colName)
	{
		//check if colName is null
		if(!is_string($colName))
		{
			throw new Exception("Parameter must be string");
		}

	    if (strpos($colName, '.') !== false) {
	    	$this->groupSql = " GROUP BY {$colName}";
	    }
	    else //add the table name
	    {
	      $this->groupSql = " GROUP BY {$this->table}.{$colName}";
	    }

		return $this;
	}

	/**
	 * function for leftjoin or join
	 * @param  string $table description
	 * @param   $col1 description
	 * @param   $operator description
	 * @param   $col2 description
	 * @return  $this description
	 * */
	public function join($jointable,$joinCol,$operator,$local,$type='INNER')
	{
		//INNER JOIN Customers ON Orders.CustomerID=Customers.CustomerID;
		$joinFormat=" {$type} JOIN %s ON %s %s %s";
	    //check local table has dot or not
	    if (strpos($local, '.') !== false) {
	      $localCol = $local;
	    }
	    else //add the table name
	    {
	        $localCol = "{$this->table}.{$local}";
	    }

	    //check join column has dot or not
	    if (strpos($joinCol, '.') !== false) {
	      $joinRef = $joinCol;
	    }
	    else //add the table name
	    {
	        $joinRef = "{$jointable}.{$joinCol}";
	    }
		$this->joinSql .= sprintf($joinFormat,$jointable,$joinRef,$operator,$localCol);
		return $this;
	}
	/**
	 * function for lefjoin
	 * @param  string $table description
	 * @param   $col1 description
	 * @param   $operator description
	 * @param   $col2 description
	 * @return  $this description
	 * */
	public function leftJoin($table,$col1,$operator,$col2)
	{
		//LEFT JOIN Customers ON Orders.CustomerID=Customers.CustomerID;
		// $joinFormat=" LEFT JOIN %s ON %s %s %s";
		// $this->joinSql .= sprintf($joinFormat,$table,$col1,$operator,$col2);
		$this->join($table,$col1,$operator,$col2,'LEFT');
		return $this;
	}
	/**
	 * function to right join
	 * @param   $table description
	 * @param   $column1 description
	 * @param   $operator description
	 * @param   $column2 description
	 * @return  $this description
	 * */
	public function rightJoin($table,$col1,$operator,$col2)
	{
		//RIGHT JOIN Customers ON Orders.CustomerID=Customers.CustomerID;
		// $joinFormat=" RIGHT JOIN %s ON %s %s %s";
		// $this->joinSql .= sprintf($joinFormat,$table,$col1,$operator,$col2);
		$this->join($table,$col1,$operator,$col2,'RIGHT');
		return $this;
	}

	/**
	 * function for offset
	 * @param   $offset description
	 * @return  $this description
	 * */
	public function offset($offset=0)
	{
		if(!is_null($offset))
		{
			$this->offset = " OFFSET {$offset}";
		}
		return $this;
	}

	/**
	 * function for limit sql data
	 * @param   $limit description
	 * @return  $this description
	 * */
	public function limit($limit=25)
	{
		if(!is_null($limit))
		{
			$this->limitSql = " LIMIT {$limit}";
		}
		return $this;
	}
  /**
   * [escape description]
   *
   * @method escape
   *
   * @param [type] $str [description]
   *
   * @return [type] [description]
   */
	public function escape($str)
	{
		$str=trim($str);
		$str=str_replace(array('"',"'","\\"), '', $str);
		return $str;
	}
}
?>
