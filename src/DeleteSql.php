<?PHP
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/
namespace MarwaDB;

use MarwaDB\Exceptions\{ArrayNotFoundException,InvalidArgumentException,NotFoundException};
use MarwaDB\WhereSqlTrait;

class DeleteSql
{
  use WhereSqlTrait;
  /**
   * [$deleteSql description]
   *
   * @var [type]
   */
  var $deleteSql;
  /**
   * [$whereSql description]
   *
   * @var [type]
   */
  var $whereSql;
  /**
   * [$whereOrSql description]
   *
   * @var [type]
   */
  var $whereOrSql; //where OR sql
  /**
   * [$whereAndSql description]
   *
   * @var [type]
   */
  var $whereAndSql; //where AND sql

	/**
	 * Update Query Format
	 * var String
	 * */
	var	$deleteQuery = 'DELETE FROM %s ';

	/**
	 * var \MarwaDB\DB
	 * */
	var $db;

	/**
	 * table name
	 * var string
	 * */
	var $table;

	/**
	 * function __construct
	 * @param   \MarwaDB\DB $db description
	 * @param  Array $data description
	 * @return  Boolean description
	 * */

	public function __construct($db,$table)
	{
		if(is_null($db) OR is_null($table))
		{
		  throw new InvalidArgumentException("Invalid Arguments");
		}
		//assign database object
		$this->db = $db;
		//set table name
		$this->table=$table;
	}

	/**
	 * function to insertSql
	 * @param  Array $paramname description
	 * @return  Boolean description
	 * */
	public function save()
	{
		//get the build sql
		$sql = $this->buildSql();

		if(!is_null($this->db) && !is_null($sql))
		{
			return $this->db->delete($sql[0],$this->placeHolders);
		}
		return false;
	}

	/**
	 * function to build Insert Sql
	 * @param  Array $paramname description
	 * @return  String description
	 * */
	public function buildSql()
	{
		$sqlArr=[];

		//replace sql place holder
		$sqlArr[0] = sprintf($this->deleteQuery,$this->table);

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

		return $sqlArr;
	}

}
