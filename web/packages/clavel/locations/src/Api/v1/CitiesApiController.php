<?php

namespace Clavel\Locations\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use App\Http\Controllers\ApiController;
use Clavel\Locations\Models\City;
use Clavel\Locations\Resources\CityResource;

class CitiesApiController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v1/province/{province}/cities",
     *     tags={"Cities"},
     *     summary="User's cities",
     *     description="Retrieve the list of cities",
     *     operationId="city-index",
     *     @OA\Parameter(
     *         name="province",
     *         in="path",
     *         description="Province ID",
     *         required=true,
     *         @OA\Schema(
     *              type="integer",
     *              format="int64"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Full city list",
     *         @OA\Header(header="X-Authorization-Token", ref="#/components/headers/X-Authorization-Token"),
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"data"},
     *                  @OA\Property(
     *                      property="data",
     *                      type="array",
     *                      minItems=0,
     *                      uniqueItems=true,
     *                      nullable=false,
     *                      @OA\Items(ref="#/components/schemas/City")
     *                  )
     *              )
     *         )
     *     ),
     *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
     *     @OA\Response(response=500, ref="#/components/responses/InternalServerError")
     * )
     */
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return City::all();
        return CityResource::collection(
            City::active()
            ->where('province_id', $request->province)
            ->orderByTranslation('name', 'ASC')
            ->get()
        )
            ->response()
            ->header('X-Authorization-Token', "{$this->getToken(request())}");
    }

    /**
     * @OA\Get(
     *     path="/api/v1/province/{province}/cities/list",
     *     tags={"Cities"},
     *     summary="User's cities",
     *     description="Retrieve the list of cities",
     *     operationId="city-list",
     *     @OA\Parameter(
     *         name="province",
     *         in="path",
     *         description="Province ID",
     *         required=true,
     *         @OA\Schema(
     *              type="integer",
     *              format="int64"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="pagination",
     *         in="query",
     *         description="Needs pagination on/off",
     *         required=false,
     *         @OA\Schema(
     *              type="boolean",
     *              default=false
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="List page",
     *         required=false,
     *         @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              default=1
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="size",
     *         in="query",
     *         description="Size of page",
     *         required=false,
     *         @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              default=64
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Full city list",
     *         @OA\Header(header="X-Authorization-Token", ref="#/components/headers/X-Authorization-Token"),
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"data"},
     *                  @OA\Property(
     *                      property="data",
     *                      type="array",
     *                      minItems=0,
     *                      uniqueItems=true,
     *                      nullable=false,
     *                      @OA\Items(ref="#/components/schemas/City")
     *                  )
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=206,
     *         description="Paginated data",
     *         @OA\Header(header="X-Authorization-Token", ref="#/components/headers/X-Authorization-Token"),
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"data","links","meta"},
     *                  @OA\Property(
     *                      property="data",
     *                      type="array",
     *                      minItems=0,
     *                      uniqueItems=true,
     *                      nullable=false,
     *                      @OA\Items(ref="#/components/schemas/City")
     *                  ),
     *                  @OA\Property(
     *                      property="links",
     *                      ref="#/components/schemas/PaginationLinks"
     *                  ),
     *                  @OA\Property(
     *                      property="meta",
     *                      ref="#/components/schemas/PaginationMeta"
     *                  )
     *              )
     *         )
     *     ),
     *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
     *     @OA\Response(response=500, ref="#/components/responses/InternalServerError")
     * )
     */
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {
            $pagination = ($request->get("pagination", 'false')== 'true' ? true : false);

            $cities = City::active()
                ->where('province_id', $request->province);

            if (!$pagination) {
                return CityResource::collection(
                    City::active()
                        ->where('province_id', $request->province)
                        ->orderByTranslation('name', 'ASC')
                        ->get()
                )
                    ->response()
                    ->header('X-Authorization-Token', "{$this->getToken($request)}");
            }

            $size = intval($request->get('size', 10));
            if (empty($size)) {
                $size = 10;
            }
            return CityResource::collection($cities->paginate($size))
                ->response()
                ->header('X-Authorization-Token', "{$this->getToken($request)}")
                ->setStatusCode(Response::HTTP_PARTIAL_CONTENT);
        } catch (\Exception $e) {
            // Something went wrong.
            return $this->respondWithCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            //return $this->respondWithCustom(Response::HTTP_INTERNAL_SERVER_ERROR,Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/cities",
     *     tags={"Cities"},
     *     summary="Create a new city",
     *     description="Create a new city",
     *     operationId="city-store",
     *     security={{"Authorization":{}}},
     *     @OA\RequestBody(
     *         description="city data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/City")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="City create",
     *         @OA\Header(header="X-Authorization-Token", ref="#/components/headers/X-Authorization-Token"),
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"data"},
     *                  @OA\Property(
     *                      property="data",
     *                      ref="#/components/schemas/City"
     *                  )
     *              )
     *         )
     *     ),
     *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
     *     @OA\Response(response=403, ref="#/components/responses/Forbidden"),
     *     @OA\Response(response=422, ref="#/components/responses/UnprocessableEntity"),
     *     @OA\Response(response=500, ref="#/components/responses/InternalServerError")
     * )
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'password' => 'required',
            'email' => 'required|email|unique:cities,email',
            'edad' => 'required|numeric|gte:1|lte:90',
        ]);

        if ($validator->fails()) {
            //return $this->respondWithCode(Response::HTTP_UNPROCESSABLE_ENTITY);
            return $this->respondWithCustom(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $validator->errors()
            );
        }


        try {
            $city = City::create($request->all());

            return (new CityResource($city))
                ->response()
                ->header('X-Authorization-Token', "{$this->getToken($request)}");
        } catch (\Exception $e) {
            // Something went wrong.
            //return $this->respondWithCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respondWithCustom(Response::HTTP_INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/cities/{id}",
     *     tags={"Cities"},
     *     summary="City detail",
     *     description="Retrieve a specific city",
     *     operationId="city-show",
     *     security={{"Authorization":{}}},
     *     @OA\Parameter(
     *         description="City ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authentication token",
     *         @OA\Header(header="X-Authorization-Token", ref="#/components/headers/X-Authorization-Token"),
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"data"},
     *                  @OA\Property(
     *                      property="data",
     *                      ref="#/components/schemas/City"
     *                  )
     *              )
     *         )
     *     ),
     *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
     *     @OA\Response(response=403, ref="#/components/responses/Forbidden"),
     *     @OA\Response(response=404, ref="#/components/responses/NotFound"),
     *     @OA\Response(response=500, ref="#/components/responses/InternalServerError")
     * )
     */
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $city = City::find($id);
            if (is_null($city)) {
                return $this->respondWithCode(Response::HTTP_FORBIDDEN);
            }

            return (new CityResource($city))
                ->response()
                ->header('X-Authorization-Token', "{$this->getToken(request())}");
        } catch (\Exception $e) {
            return $this->respondWithCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            //return $this->respondWithCustom(Response::HTTP_INTERNAL_SERVER_ERROR,Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/cities/{id}",
     *     tags={"Cities"},
     *     summary="Update an city",
     *     description="Update an city",
     *     operationId="city-update",
     *     security={{"Authorization":{}}},
     *     @OA\Parameter(
     *         description="City ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="City data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/City")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="City update",
     *         @OA\Header(header="X-Authorization-Token", ref="#/components/headers/X-Authorization-Token"),
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"data"},
     *                  @OA\Property(
     *                      property="data",
     *                      ref="#/components/schemas/City"
     *                  )
     *              )
     *         )
     *     ),
     *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
     *     @OA\Response(response=403, ref="#/components/responses/Forbidden"),
     *     @OA\Response(response=404, ref="#/components/responses/NotFound"),
     *     @OA\Response(response=500, ref="#/components/responses/InternalServerError")
     * )
     */
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'password' => 'required',
            'email' => 'required|email|unique:cities,email',
            'edad' => 'required|numeric|gte:1|lte:90',
        ]);

        if ($validator->fails()) {
            //return $this->respondWithCode(Response::HTTP_UNPROCESSABLE_ENTITY);
            return $this->respondWithCustom(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $validator->errors()
            );
        }

        try {
            $city = City::find($id);
            if (is_null($city)) {
                return $this->respondWithCode(Response::HTTP_NOT_FOUND);
            }

            $city->update($request->all());

            return (new CityResource($city))
                ->response()
                ->header('X-Authorization-Token', "{$this->getToken($request)}");
        } catch (\Exception $e) {
            // Something went wrong.
            //return $this->respondWithCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respondWithCustom(Response::HTTP_INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/cities/{id}",
     *     tags={"Cities"},
     *     summary="Delete an city",
     *     description="Delete an city",
     *     operationId="city-delete",
     *     security={{"Authorization":{}}},
     *     @OA\Parameter(
     *         description="City ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="City update",
     *         @OA\Header(header="X-Authorization-Token", ref="#/components/headers/X-Authorization-Token")
     *     ),
     *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
     *     @OA\Response(response=403, ref="#/components/responses/Forbidden"),
     *     @OA\Response(response=404, ref="#/components/responses/NotFound"),
     *     @OA\Response(response=500, ref="#/components/responses/InternalServerError")
     * )
     */
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $city = City::find($id);
            if (is_null($city)) {
                return $this->respondWithCode(Response::HTTP_NOT_FOUND);
            }

            $city->delete();

            return response(null, Response::HTTP_NO_CONTENT)
                ->header('X-Authorization-Token', "{$this->getToken(request())}");
        } catch (\Exception $e) {
            // Something went wrong.
            //return $this->respondWithCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            return $this->respondWithCustom(Response::HTTP_INTERNAL_SERVER_ERROR, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
}
