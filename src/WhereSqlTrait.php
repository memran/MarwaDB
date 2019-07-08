<?PHP
/**
 * @author    Mohammad Emran <memran.dhk@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/memran
 * @see      http://www.memran.me
 **/

namespace MarwaDB;

trait WhereSqlTrait
{

	var $placeHolders = [];

	/**
	 * function for where raw
	 * @param  string $columns description
	 * @return  $this description
	 * */
	public function whereRaw($searchQuery,$param=null)
	{
		//check if search query null
		if(is_null($searchQuery))
		{
			throw new Exception("Where condition can not be null");
		}

		//wheresql format
		$whereFormat=' WHERE %s';

		//sprintf with format
		$this->whereSql = sprintf($whereFormat,$searchQuery);
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
	public function orWhereRaw($columns,$param=null)
	{
		$whereFormat=' OR %s';
		$this->whereOrSql = sprintf($whereFormat,$columns);
		if(!is_null($param))
		{
			$this->placeHolders=array_merge($this->placeHolders,$param);
		}
		return $this;
	}

	/**
	 * function for andWhereRaw
	 * @param  string $columns description
	 * @return  $this description
	 * */
	public function andWhereRaw($columns,$param = null)
	{
		$whereFormat=' AND %s';
		$this->whereAndSql = sprintf($whereFormat,$columns);

		if(!is_null($param))
		{
			$this->placeHolders=array_merge($this->placeHolders,$param);
		}
		return $this;
	}


	/**
	 * function for orWhereRaw
	 * @param  string $column description
	 * @param  string  $data description
	 * @return  $this description
	 * */
	public function where($column,$search,$operator="=")
	{
		$colSql= "{$column} {$operator} ?";
		$this->whereRaw($colSql,[$search]);
		return $this;
	}

	/**
	 * function for whereBetween
	 * @param  string $colum description
	 * @param  string $data description
	 * @return  $this description
	 * */
	public function whereBetween($column,$data)
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
	public function orWhereBetween($column,$data)
	{
		$colSql= "{$column} BETWEEN ? AND ?";
		$this->orWhereRaw($colSql,$data);
		return $this;
	}
	/**
	 * function for WhereNotBetween
	 * @param  string $colum description
	 * @param  string $data description
	 * @return  $this description
	 * */
	public function whereNotBetween($column,$data)
	{
		$colSql= "{$column} NOT BETWEEN ? AND ?";
		$this->WhereRaw($colSql,$data);
		return $this;
	}



	/**
	 * function for orWhereNotBetween
	 * @param  string $colum description
	 * @param  string $data description
	 * @return  $this description
	 * */
	public function orWhereNotBetween($column,$data)
	{
		$colSql= "{$column} NOT BETWEEN ? AND ?";
		$this->orWhereRaw($colSql,$data);
		return $this;
	}

	/**
	 * function for whereIn
	 * @param  string $colum description
	 * @param  arary $data description
	 * @return  $this description
	 * */
	public function whereIn($column,$data)
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
	public function whereNotIn($column,$data)
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
	public function orWhereIn($column,$data)
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
	public function orWhereNotIn($column,$data)
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
	public function whereNull($column)
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
