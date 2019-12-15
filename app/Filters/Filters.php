<?php 

namespace App\Filters;

use App\Filters\Interfaces\Filterable;
use Illuminate\Http\Request;

abstract class Filters implements Filterable {

	protected $request;

	protected $builder;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function apply ($builder)
	{
		$this->builder = $builder;

		foreach($this->getFilters() as $filter)
		{
			$filterMethod = "filterBy".ucfirst($filter);
			if (method_exists($this, $filterMethod) && $this->request->has($filter))
			{
				$this->{$filterMethod}();
			}
		}
	}

}