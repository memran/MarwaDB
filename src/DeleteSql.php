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

class DeleteSql
{
	use WhereSqlTrait;
	/**
	 * var string
	 * */
	var $deleteSql=null;

	/**
	 * var string
	 * */
  var $whereSql=null;  //where sql
  var $whereOrSql=null; //where OR sql
  var $whereAndSql=null; //where AND sql

	/**
	 * Update Query Format
	 * var String
	 * */
	var	$deleteQuery = 'DELETE FROM %s ';

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
	 * function __construct
	 * @param   \MarwaDB\DB $db description
	 * @param  Array $data description
	 * @return  Boolean description
	 * */

	public function __construct($db,$table)
	{
    if(is_null($db) OR is_null($table))
    {
      throw new Exception("Table or DB can not null");
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
				$result = $this->db->delete($sql[0],$this->placeHolders);
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
