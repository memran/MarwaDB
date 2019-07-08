<?PHP
namespace MarwaDB;

use MarwaDB\Exceptions\ArrayNotFoundException;
use MarwaDB\Exceptions\NotFoundException;
use MarwaDB\InsertSql;
use MarwaDB\UpdateSql;

class QueryBuilder
{

	/**
	 * var string table name
	 * */
	var $table;

	/**
	 * var object
	 * */
	var $db=null;

	/**
	 * function __construct
	 * */
	public function __construct($db,$name)
	{
		$this->db = $db;
		$this->table = trim($name);
	}

	/**
	 *  function select database query builder
	 * @param  $columns description
	 * @return  $this description
	 * */
	public function select(...$columns)
	{
		//check if columns is not array
		if(!is_array($columns))
		{
			throw new ArrayNotFoundException("Columns is not array");
		}
		//check columns is empty array
		if (empty($columns))
		{
     		$columns = ["*"];
		}

		$select= new SelectSql($this->db,$this->table,$columns);
		return $select;
	}

	/**
	 *  function select database query builder
	 * @param  $columns description
	 * @return  $this description
	 * */
	public function distinct(...$columns)
	{
		//check if columns is not array
		if(!is_array($columns))
		{
			throw new ArrayNotFoundException("Columns is not array");
		}
		//check columns is empty array
		if (empty($columns))
		{
     		$columns = ["*"];
		}

		$select= new SelectSql($this->db,$this->table,$columns);
		$select->distinct();
		return $select;
	}

	/**
	 * function to count data from column
	 * @param   $columnname description
	 * @param   $alias description
	 * */
	public function count($column="*" , $alias=null)
	{
		$colSql[0]="COUNT({$column})";

		if(!is_null($alias))
		{
			$colSql[0].= " AS ".$alias;
		}

		$select= new SelectSql($this->db,$this->table,$colSql,true);
		return $select->get();
	}

	/**
	 * function to sum a column data from sql
	 * @param   $columnname description
	 * @param   $alias description
	 * */
	public function sum($column="*",$alias=null)
	{
		$colSql[0]= "SUM({$column})";
		if(!is_null($alias))
		{
			$colSql[0] .= " AS ".$alias;
		}
		$select= new SelectSql($this->db,$this->table,$colSql,true);
		return $select->get();
	}

	/**
	 * function to avg a column data from sql
	 * @param   $columnname description
	 * @param   $alias description
	 * */
	public function avg($column="*",$alias=null)
	{
		$colSql[0]= "AVG({$column})";
		if(!is_null($alias))
		{
			$colSql[0] .= " AS ".$alias;
		}
		$select= new SelectSql($this->db,$this->table,$colSql,true);
		return $select->get();
	}

	/**
	 * function to max a column data from sql
	 * @param   $columnname description
	 * @param   $alias description
	 * */
	public function max($column="*",$alias=null)
	{
		$colSql[0]= "MAX({$column})";
		if(!is_null($alias))
		{
			$colSql[0] .= " AS ".$alias;
		}
		$select = new SelectSql($this->db,$this->table,$colSql,true);
		return $select->get();
	}

	/**
	 * function to min a column data from sql
	 * @param   $columnname description
	 * @param   $alias description
	 * */
	public function min($column="*",$alias=null)
	{
		$colSql[0]= "MIN({$column})";
		if(!is_null($alias))
		{
			$colSql[0] .= " AS ".$alias;
		}
		$select = new SelectSql($this->db,$this->table,$colSql,true);
		return $select->get();
	}

	/**
	 * function for whereExists
	 * @param  Closure $paramname description
	 * @return  PDOResult description
	 * */
	public function whereExists($func)
	{
			$select= new SelectSql($this->db,$this->table,['*']);
			$that=$this;
			$sqlWhereExists = call_user_func_array($func, [$that]);
			return $select->whereRaw("EXISTS({$sqlWhereExists})")->get();
	}
	/**
	 * function to insert data to table
	 * @param   array $data description
	 * @return   description
	 * */
	public function insert($data)
	{
		//check if array
		if(!is_array($data))
		{
			throw new ArrayNotFoundException("Parameter must be Array");
		}

		$insert =  new InsertSql($this->db,$this->table,$data);
		return $insert;
	}

	/**
	 * function to insert data to table
	 * @param   array $data description
	 * @return   description
	 * */
	public function insertAndGetId($data)
	{
		//check if array
		if(!is_array($data))
		{
			throw new ArrayNotFoundException("Parameter must be Array");
		}

		$insert =  new InsertSql($this->db,$this->table,$data);
		$insert->save();
		return $insert->getLastId();

	}
     /**
	 * function to insert data to table
	 * @param   array $data description
	 * @return   description
	 * */
	public function update($data)
	{
		if(!is_array($data))
		{
			throw new ArrayNotFoundException("Parameter must be Array");
		}
		$update =  new UpdateSql($this->db,$this->table,$data);
		return $update;
	}

	/**
	 * function to delete data to table
	 * @return  Boolean description
	 * */
	public function delete()
	{
		$delete =  new DeleteSql($this->db,$this->table);
		return $delete;
	}

}


?>
