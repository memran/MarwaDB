<?php
	
	namespace MarwaDB\Builders\Common;
	
	interface BuilderInterface {
		
		/**
		 * @param string $name
		 * @return mixed
		 */
		public function table(string $name);
		
		public function setData(array $data);
		/**
		 * @return mixed
		 */
		public function formatSql();
		
		/**
		 * @return string
		 */
		public function getSql();
	}
