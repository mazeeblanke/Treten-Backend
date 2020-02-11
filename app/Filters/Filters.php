<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\Interfaces\Filterable;
/**
 * This class facilitates filtering
 */
abstract class Filters implements Filterable {

    /**
     * The http request
     *
     * @var [Illuminate\Http\Request]
     */
	protected $request;

    /**
     * The query builder
     *
     * @var [Illuminate\Database\Query\Builder]
     */
	protected $builder;

    /**
     * Initialize the the class
     *
     * @param Request $request
     */
	public function __construct(Request $request)
	{
		$this->request = $request;
    }

    /**
     * Get the filters
     *
     * @return array
     */
    public function getFilters (): array
    {
        return $this->filters;
    }

    /**
     * Apply the builder to this filter
     *
     * @param [Illuminate\Database\Query\Builder] $builder
     * @return void
     */
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

        if (method_exists($this, 'applyGlobalQueries')){
            $this->applyGlobalQueries();
        }
    }

}
