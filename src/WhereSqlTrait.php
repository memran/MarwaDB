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
  		$this->whereOrSql=" OR {$columns}";
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
    $this->whereAndSql=" AND {$columns}";
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
    $colSql ='';

    if(is_string($column) && !is_null($search))
    {
      $colSql= "{$column} {$operator} ?";
      $this->whereRaw($colSql,[$search]);
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
    $colSql= "{$column} {$operator} ?";
		$this->andWhereRaw($colSql,[$placeHolders]);
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
    $colSql ='';

    if(is_string($sql) && !is_null($search))
    {
      $colSql= "({$sql})";
      $this->whereRaw($colSql,$search);
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
    $colSql ='';

    if(is_string($sql) && !is_null($search))
    {
      $colSql= "({$sql})";
      $this->orWhereRaw($colSql,$search);
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
    $colSql ='';

    if(is_string($sql) && !is_null($search))
    {
      $colSql= "({$sql})";
      $this->andWhereRaw($colSql,$search);
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
		$colSql = "{$column} LIKE ?";

		$this->whereRaw($colSql,[$placeHolders]);
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
		$colSql = "{$column} LIKE ?";

		$this->orWhereRaw($colSql,[$placeHolders]);
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
		$colSql = "{$column} LIKE ?";
		$this->andWhereRaw($colSql,[$placeHolders]);
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
		$colSql = "{$column} NOT LIKE ?";

		$this->whereRaw($colSql,[$placeHolders]);
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
    $colSql = "{$column} NOT LIKE ?";

    $this->orWhereRaw($colSql,[$placeHolders]);
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
		$colSql = "{$column} NOT LIKE ?";

		$this->andWhereRaw($colSql,[$placeHolders]);
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
		$colSql= "{$column} BETWEEN ? AND ?";
		$this->whereRaw($colSql,$data);
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
		$colSql= "{$column} BETWEEN ? AND ?";
		$this->orWhereRaw($colSql,$data);
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
  		$colSql= "{$column} BETWEEN ? AND ?";
  		$this->andWhereRaw($colSql,$data);
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
  		$colSql= "{$column} NOT BETWEEN ? AND ?";
  		$this->whereRaw($colSql,$data);
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
		$colSql= "{$column} NOT BETWEEN ? AND ?";
		$this->orWhereRaw($colSql,$data);
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
		$colSql= "{$column} NOT BETWEEN ? AND ?";
		$this->andWhereRaw($colSql,$data);
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
		$colSql = "{$column} IN ({$param})";
		$this->whereRaw($colSql,$data);
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
		$colSql = "{$column} NOT IN ({$param})";
		$this->whereRaw($colSql,$data);
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
		$colSql = "{$column} IN ({$param})";
		$this->orWhereRaw($colSql,$data);
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
		$colSql = "{$column} NOT IN ({$param})";
		$this->orWhereRaw($colSql,$data);
		return $this;
	}

   /**
	 * function for orWhereBetween
	 * @param  string $colum description
	 * @return  $this description
	 * */
	public function whereNull(string $column)
	{
		$colSql = "{$column} IS NULL";
		$this->whereRaw($colSql);
		return $this;
	}

	/**
	 * function for orWhereBetween
	 * @param  string $colum description
	 * @return  $this description
	 * */
	public function whereNotNull($column)
	{
		$colSql = "{$column} IS NOT NULL";
		$this->whereRaw($colSql);
		return $this;
	}

/**
	 * function for orWhereBetween
	 * @param  string $colum description
	 * @return  $this description
	 * */
	public function orWhereNull($column)
	{
		$colSql = "{$column} IS NULL";
		$this->orWhereRaw($colSql);
		return $this;
	}

	/**
	 * function for orWhereBetween
	 * @param  string $colum description
	 * @return  $this description
	 * */
	public function orWhereNotNull($column)
	{
		$colSql = "{$column} IS NOT NULL";
		$this->orWhereRaw($colSql);
		return $this;
	}

  /**
 * function for whereDate
 * @param  string $colum description
 * @return  $this description
 * */
	public function whereDate($column,$data)
	{
		$colSql = "DATE({$column}) = ?";
		$this->whereRaw($colSql,explode(" ",$data));
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
		$colSql = "EXTRACT(MONTH FROM {$column}) = ?";
		$this->whereRaw($colSql,explode(" ",$month));
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
		$colSql = "EXTRACT(DAY FROM {$column}) = ?";
		$this->whereRaw($colSql,explode(" ",$day));
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
		$colSql = "EXTRACT(YEAR FROM {$column}) = ?";
		$this->whereRaw($colSql,explode(" ",$year));
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
		$colSql = "TIME({$column}) = ?";
		$this->whereRaw($colSql,explode(" ",$time));
		return $this;
	}

}
