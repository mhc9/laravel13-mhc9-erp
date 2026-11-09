<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Laravel\Passport\Contracts\AuthorizationViewResponse;

class PassportCustomResponse implements AuthorizationViewResponse
{
    /**
     * The parameters to be passed to the view.
     *
     * @var array
     */
    protected array $parameters = [];

    /**
     * Set the parameters for the response.
     *
     * @param  array  $parameters
     * @return $this
     */
    public function withParameters(array $parameters = [])
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return view('auth.oauth.authorize', $this->parameters);
    }
}
