<?php
	
	
	namespace MarwaDB\Builders\Common;
	
	
	use MarwaDB\Exceptions\NotFoundException;
	
	trait BuildQueryTrait {
		
		/**
		 * @throws NotFoundException
		 */
		public function buildQuery()
		{
			$this->_builder->table($this->_table);
			
			if ( $this->_type === 'select' )
			{
				$this->buildSelectQuery();
			}
			else
			{
				if ( $this->_type === 'insert' )
				{
					$this->buildInsertQuery();
				}
				else
				{
					if ( $this->_type === 'delete' )
					{
						$this->buildDeleteQuery();
					}
					else
					{
						if ( $this->_type === 'update' )
						{
							$this->buildUpdateQuery();
						}
						else
						{
							throw new NotFoundException("Invalid Query Type");
						}
					}
				}
			}
		}
	}