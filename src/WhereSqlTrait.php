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

trait WhereSqlTrait
{

  /**
   * [$placeHolders description]
   *
   * @var array
   */
	var $placeHolders = [];

  /**
	 * function for where raw
	 * @param  string $columns description
	 * @return  $this description
	 * */
	public function whereRaw(string $searchQuery,$param=null)
	{
		//wheresql format
    $whereFormat=" WHERE {$searchQuery}";

		//sprintf with format
		//$this->whereSql = sprintf($whereFormat,$searchQuery);
		$this->whereSql = $whereFormat;
		//check if param is not null
		if(!is_null($param))
		{
			$this->placeHolders = $param;
		}
		return $this;
	}
	/**
	 * function for orWhereRaw
	 * @param  string $columns description
	 * @return  $this description
	 * */
	public function orWhereRaw(string $columns,$params=null)
	{
		$this->whereOrSql.=" OR {$columns}";
    //check if placeHolders are not null
		if(!is_null($params))
		{
			$this->placeHolders=array_merge($this->placeHolders,$params);
		}
		return $this;
	}

	/**
	 * function for andWhereRaw
	 * @param  string $columns description
	 * @return  $this description
	 * */
	public function andWhereRaw(string $columns,$params = null)
	{
   	$this->whereAndSql.=" AND {$columns}";
		//$this->whereAndSql = sprintf($whereFormat,$columns);

		if(!is_null($params))
		{
			$this->placeHolders=array_merge($this->placeHolders,$params);
		}
		return $this;
	}


	/**
	 * function for orWhereRaw
	 * @param  string $column description
	 * @param  string  $data description
	 * @return  $this description
	 * */
	public function where($column,string $search=null,$operator="=")
	{
    if(is_string($column) && !is_null($search))
    {
      $this->whereRaw("{$column} {$operator} ?",[$search]);
    }
		return $this;
	}

  /**
   * [orWhere description]
   *
   * @method orWhere
   *
   * @param string $column [description]
   * @param string $placeHolders [description]
   * @param string $operator [description]
   *
   * @return [type] [description]
   */
  public function orWhere(string $column,string $placeHolders,$operator="=")
	{
    $colSql= "{$column} {$operator} ?";
		$this->orWhereRaw($colSql,[$placeHolders]);
		return $this;
	}

  /**
   * [andWhere description]
   *
   * @method andWhere
   *
   * @param string $column [description]
   * @param string $placeHolders [description]
   * @param string $operator [description]
   *
   * @return [type] [description]
   */
  public function andWhere(string $column,string $placeHolders,$operator="=")
	{
		$this->andWhereRaw("{$column} {$operator} ?",[$placeHolders]);
		return $this;
	}

  /**
   * [subWhere description]
   *
   * @method subWhere
   *
   * @param string $sql [description]
   * @param array $search [description]
   *
   * @return [type] [description]
   */
  public function subWhere(string $sql,array $search)
  {
    if(is_string($sql) && !is_null($search))
    {
      $this->whereRaw("({$sql})",$search);
    }
    return $this;
  }

  /**
   * [orSubWhere description]
   *
   * @method orSubWhere
   *
   * @param string $sql [description]
   * @param array $search [description]
   *
   * @return [type] [description]
   */
  public function orSubWhere(string $sql,array $search)
  {
    if(is_string($sql) && !is_null($search))
    {
      $this->orWhereRaw("({$sql})",$search);
    }
    return $this;
  }

  /**
   * [andSubWhere description]
   *
   * @method andSubWhere
   *
   * @param string $sql [description]
   * @param array $search [description]
   *
   * @return [type] [description]
   */
  public function andSubWhere(string $sql,array $search)
  {
    if(is_string($sql) && !is_null($search))
    {
      $this->andWhereRaw("({$sql})",$search);
    }
    return $this;
  }

  /**
   * [whereLike description]
   *
   * @method whereLike
   *
   * @param string $column [description]
   * @param string $placeHolders [description]
   *
   * @return [type] [description]
   */
  public function whereLike(string $column,string $placeHolders)
	{
		$this->whereRaw("{$column} LIKE ?",[$placeHolders]);
		return $this;
	}


  /**
   * [orWhereLike description]
   *
   * @method orWhereLike
   *
   * @param string $column [description]
   * @param string $placeHolders [description]
   *
   * @return [type] [description]
   */
  public function orWhereLike(string $column,string $placeHolders)
	{
	  $this->orWhereRaw("{$column} LIKE ?",[$placeHolders]);
		return $this;
	}

  /**
   * [andWhereLike description]
   *
   * @method andWhereLike
   *
   * @param string $column [description]
   * @param string $placeHolders [description]
   *
   * @return [type] [description]
   */
  public function andWhereLike(string $column,string $placeHolders)
	{
		$this->andWhereRaw("{$column} LIKE ?",[$placeHolders]);
		return $this;
	}

  /**
   * [whereNotLike description]
   *
   * @method whereNotLike
   *
   * @param string $column [description]
   * @param string $placeHolders [description]
   *
   * @return [type] [description]
   */
  public function whereNotLike(string $column,string $placeHolders)
	{
		$this->whereRaw("{$column} NOT LIKE ?",[$placeHolders]);
		return $this;
	}
  /**
   * [orWhereNotLike description]
   *
   * @method orWhereNotLike
   *
   * @param string $column [description]
   * @param string $placeHolders [description]
   *
   * @return [type] [description]
   */
  public function orWhereNotLike(string $column,string $placeHolders)
  {
    $this->orWhereRaw("{$column} NOT LIKE ?",[$placeHolders]);
    return $this;
  }

  /**
   * [andWhereNotLike description]
   *
   * @method andWhereNotLike
   *
   * @param string $column [description]
   * @param string $placeHolders [description]
   *
   * @return [type] [description]
   */
  public function andWhereNotLike(string $column,string $placeHolders)
	{
		$this->andWhereRaw("{$column} NOT LIKE ?",[$placeHolders]);
		return $this;
	}

	/**
	 * function for whereBetween
	 * @param  string $colum description
	 * @param  string $data description
	 * @return  $this description
	 * */
	public function whereBetween(string $column,array $data)
	{
		$this->whereRaw("{$column} BETWEEN ? AND ?",$data);
		return $this;
	}

	/**
	 * function for orWhereBetween
	 * @param  string $colum description
	 * @param  string $data description
	 * @return  $this description
	 * */
	public function orWhereBetween(string $column,array $data)
	{
		$this->orWhereRaw("{$column} BETWEEN ? AND ?",$data);
		return $this;
	}

    /**
     * [andWhereBetween description]
     *
     * @method andWhereBetween
     *
     * @param string $column [description]
     * @param array $data [description]
     *
     * @return [type] [description]
     */
    public function andWhereBetween(string $column,array $data)
  	{
  		$this->andWhereRaw("{$column} BETWEEN ? AND ?",$data);
  		return $this;
  	}


	/**
	 * function for WhereNotBetween
	 * @param  string $colum description
	 * @param  string $data description
	 * @return  $this description
	 * */
	public function whereNotBetween(string $column,array $data)
	{
		$this->whereRaw("{$column} NOT BETWEEN ? AND ?",$data);
		return $this;
	}

	/**
	 * function for orWhereNotBetween
	 * @param  string $colum description
	 * @param  string $data description
	 * @return  $this description
	 * */
	public function orWhereNotBetween(string $column,array $data)
	{
		$this->orWhereRaw("{$column} NOT BETWEEN ? AND ?",$data);
		return $this;
	}

  /**
   * [andWhereNotBetween description]
   *
   * @method andWhereNotBetween
   *
   * @param string $column [description]
   * @param array $data [description]
   *
   * @return [type] [description]
   */
  public function andWhereNotBetween(string $column,array $data)
	{
		$this->andWhereRaw("{$column} NOT BETWEEN ? AND ?",$data);
		return $this;
	}

	/**
	 * function for whereIn
	 * @param  string $colum description
	 * @param  arary $data description
	 * @return  $this description
	 * */
	public function whereIn(string $column,array $data)
	{
		$placeHolders=[];
		//check if data is array
		if(is_array($data))
		{
			foreach ($data as $key => $value) {
				# code...
				array_push($placeHolders,"?");
			}
		}
		else
		{
			throw new ArrayNotFoundException("Must be array");
		}
		$param = implode(',',$placeHolders);
		$this->whereRaw("{$column} IN ({$param})",$data);
		return $this;
	}

	/**
	 * function for whereNotIn
	 * @param  string $colum description
	 * @param  arary $data description
	 * @return  $this description
	 * */
	public function whereNotIn(string $column,array $data)
	{
		$placeHolders=[];
		//check if data is array
		if(is_array($data))
		{
			foreach ($data as $key => $value) {
				# code...
				array_push($placeHolders,"?");
			}
		}
		else
		{
			throw new ArrayNotFoundException("Must be array");
		}
		$param = implode(',',$placeHolders);
		$this->whereRaw("{$column} NOT IN ({$param})",$data);
		return $this;
	}

	/**
	 * function for orWhereIn
	 * @param  string $colum description
	 * @param  arary $data description
	 * @return  $this description
	 * */
	public function orWhereIn(string $column,array $data)
	{
		$placeHolders=[];
		//check if data is array
		if(is_array($data))
		{
			foreach ($data as $key => $value) {
				# code...
				array_push($placeHolders,"?");
			}
		}
		else
		{
			throw new ArrayNotFoundException("Must be array");
		}
		$param = implode(',',$placeHolders);
		$this->orWhereRaw("{$column} IN ({$param})",$data);
		return $this;
	}

	/**
	 * function for orWhereNotIn
	 * @param  string $colum description
	 * @param  arary $data description
	 * @return  $this description
	 * */
	public function orWhereNotIn(string $column,array $data)
	{
		$placeHolders=[];
		//check if data is array
		if(is_array($data))
		{
			foreach ($data as $key => $value) {
				# code...
				array_push($placeHolders,"?");
			}
		}
		else
		{
			throw new ArrayNotFoundException("Must be array");
		}
		$param = implode(',',$placeHolders);
		$this->orWhereRaw( "{$column} NOT IN ({$param})",$data);
		return $this;
	}

   /**
	 * function for orWhereBetween
	 * @param  string $colum description
	 * @return  $this description
	 * */
	public function whereNull(string $column)
	{
		$this->whereRaw("{$column} IS NULL");
		return $this;
	}

	/**
	 * function for orWhereBetween
	 * @param  string $colum description
	 * @return  $this description
	 * */
	public function whereNotNull($column)
	{
		$this->whereRaw("{$column} IS NOT NULL");
		return $this;
	}

  /**
	 * function for orWhereBetween
	 * @param  string $colum description
	 * @return  $this description
	 * */
	public function orWhereNull($column)
	{
		$this->orWhereRaw( "{$column} IS NULL");
		return $this;
	}

	/**
	 * function for orWhereBetween
	 * @param  string $colum description
	 * @return  $this description
	 * */
	public function orWhereNotNull($column)
	{
		$this->orWhereRaw("{$column} IS NOT NULL");
		return $this;
	}

  /**
 * function for whereDate
 * @param  string $colum description
 * @return  $this description
 * */
	public function whereDate($column,$data)
	{
		$this->whereRaw("DATE({$column}) = ?",explode(" ",$data));
		return $this;
	}

	 /**
	 * function for whereMonth
	 * @param  string $column description
	 * @param  string $month description
	 * @return  $this description
	 * */
	public function whereMonth($column,$month)
	{
		$this->whereRaw("EXTRACT(MONTH FROM {$column}) = ?",explode(" ",$month));
		return $this;
	}

	/**
	 * function for whereDay
	 * @param  string $column description
	 * @param  string $month description
	 * @return  $this description
	 * */
	public function whereDay($column,$day)
	{
		$this->whereRaw("EXTRACT(DAY FROM {$column}) = ?",explode(" ",$day));
		return $this;
	}

	/**
	 * function for whereYear
	 * @param  string $column description
	 * @param  string $month description
	 * @return  $this description
	 * */
	public function whereYear($column,$year)
	{
		$this->whereRaw("EXTRACT(YEAR FROM {$column}) = ?",explode(" ",$year));
		return $this;
	}

	/**
	 * function for whereTime
	 * @param  string $column description
	 * @param  string $month description
	 * @return  $this description
	 * */
	public function whereTime($column,$time)
	{
		$this->whereRaw("TIME({$column}) = ?",explode(" ",$time));
		return $this;
	}

}
