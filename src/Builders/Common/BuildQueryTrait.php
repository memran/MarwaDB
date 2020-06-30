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
			
			switch ($this->_type)
			{
				case 'select':
					$this->buildSelectQuery();
					break;
				case 'insert':
					$this->buildInsertQuery();
					break;
				case 'delete':
					$this->buildDeleteQuery();
					break;
				case 'update':
					$this->buildUpdateQuery();
					break;
				default:
					throw new NotFoundException("Invalid Query Type {$this->_type}");
			}
		}
	}