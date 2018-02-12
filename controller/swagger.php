<?php 
/**
 * @SWG\Info(
 *   title="Fischer Homes API",
 *   description="A simple way to get and put data in databases!",
 *   version="1.0.0",
 *   @SWG\Contact(
 *     email="thudson@fischerhomes.com",
 *     name="Ty Hudson II",
 *   ),
 *   @SWG\License(
 *     name="MIT",
 *     url="http://github.com/gruntjs/grunt/blob/master/LICENSE-MIT"
 *   ),
 *   termsOfService=""
 * )
 * @SWG\Swagger(
 *   host="w0lf.ddns.net",
 *   basePath="/api",
 *   schemes={"https"},
 *   produces={"application/json"},
 *   consumes={"application/json"},
 *   @SWG\ExternalDocumentation(
 *     description="find more info here",
 *     url="https://w0lf.ddns.net/"
 *   )
 * )
 */

/**
 * @SWG\Tag(
 *   name="Token",
 *   description="Everything you will need to work with tokens."
 * )
 * @SWG\Tag(
 *   name="Community",
 *   description="Everything you will need to work with Communities."
 * )
 * @SWG\Tag(
 *   name="User",
 *   description="Access to the Data Integrator user.  For now."
 * )
 * @SWG\Tag(
 *   name="SelectionsAppointment",
 *   description="Selections Appointments access."
 * )
 * @SWG\Tag(
 *   name="Realtor",
 *   description="Realtor general information."
 * )
 * @SWG\Tag(
 *   name="Job_Information",
 *   description="Pervasive Job Information object."
 * )
 * @SWG\Tag(
 *   name="Lead",
 *   description="CRM Lead Object."
 * )
 */

/**
	* @SWG\Get(
	*     path="/tokens/",
	*     summary="Returns all api tokens.",
	*     description="Returns all api tokens from the Fischer Homes API.",
	*     operationId="getAllTokens",
	*	  tags={"Token"},
	*     produces={"application/json"},
	*     @SWG\Parameter(
	*         name="Authorization",
	*         in="header",
	*         description="pass the auth token in the header to authorize and get a json responce.",
	*         required=true,
	*         type="string",
	*         @SWG\Items(type="string"),
	*         collectionFormat="csv"
	*     ),
	*     @SWG\Response(
	*         response=200,
	*         description="Tokens Response",
	*         @SWG\Schema(
	*             type="json",
	*             @SWG\Items(ref="#/definitions/token")
	*         ),
	*     ),
	*     @SWG\Response(
	*         response="default",
	*         description="null",
	*         @SWG\Schema(
	*             ref="#/definitions/token"
	*         )
	*     )
	* )
*/
?>