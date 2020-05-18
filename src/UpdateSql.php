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
use MarwaDB\WhereSqlTrait;

class UpdateSql
{
	use WhereSqlTrait;
	/**
	 * var string
	 * */
	var $updateSql=null;

  /**
  * var string
  * */
  var $whereSql=null;  //where sql
  /**
  * [$whereOrSql description]
  *
  * @var [type]
  */
  var $whereOrSql=null; //where OR sql
  /**
  * [$whereAndSql description]
  *
  * @var [type]
  */
   var $whereAndSql=null; //where AND sql
	/**
	 * Update Query Format
	 * var String
	 * */
	var	$updateQuery = 'UPDATE %s SET %s';

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
	 * var array
	 * */
	var $data=[];

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
    if(!is_array($data))
    {
      throw new ArrayNotFoundException("Parameter must be Array");
    }

		//assign database object
		$this->db = $db;
		//set table name
		$this->table=$table;

		//check if array is multi-dimensational
		if($this->is_multi($data))
		{
			throw new \Exception("multi-dimentional array is not accepted");
		}

		//set data to update
		$this->data = $data;
	}

	/**
	 * function to insertSql
	 * @param  Array $paramname description
	 * @return  Boolean description
	 * */
	public function save()
	{
		//get the build sql
		list($sql,$placeholders)=$this->buildSql();
		if(!is_null($this->db) && !is_null($sql))
		{
			return $this->db->update($sql,$placeHolders);
		}

		return false;
	}

	/**
   * [buildSql description]
   *
   * @method buildSql
   *
   * @return array [description]
   */
	public function buildSql() : array
	{
		$columns=[];
		$value =[];
		$placeHolders=null;
		$sqlArr=[];
		//loop through array
		foreach($this->data as $col => $val)
		{
			$placeHolders = $col." = ?";
			//inject column name
			array_push($columns,$placeHolders);
			//inject column value
			array_push($value, $val);
		}

		//implode column with comma(,)
		$colSql = implode(",",$columns);

		//replace sql place holder
		$sqlArr[0] = sprintf($this->updateQuery,$this->table,$colSql);

    if(!is_null($this->whereSql))
    {
     $sqlArr[0].= $this->whereSql;
     //check if not null whereOrSql
     if(!is_null($this->whereOrSql))
      {
        $sqlArr[0].=$this->whereOrSql;
      }
      //check if not null whereAndSql
      if(!is_null($this->whereAndSql))
      {
        $sqlArr[0].= $this->whereAndSql;
      }
     }

		$sqlArr[1] = $value;

		$sqlArr[1]=array_merge($sqlArr[1],$this->placeHolders);

		return $sqlArr;
	}

	/**
	 * function to check if array is multi-dimensional or not
	 * @param  Array $a description
	 * @return  Boolean description
	 * */
	public function is_multi($a) {
  	$rv = array_filter($a,'is_array');
  	if(count($rv)>0) return true;
  	return false;
	}

}
