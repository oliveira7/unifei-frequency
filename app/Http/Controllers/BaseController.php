<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseFormRequest;
use App\Http\Resources\DefaultCollection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BaseController
{
    use AuthorizesRequests, DispatchesJobs;

    protected $service;

    protected $jsonResource;

    /**
     * List resources.
     *
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $resources = $this->service->index($request->all());

        return new DefaultCollection($this->jsonResource, $resources);
    }

    /**
     * Show resource details.
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        $resource = $this->service->show($id);

        if (!$resource) {
            return $this->_notFoundError(['message' => __('messages.api.error.not.found')]);
        }

        return new $this->jsonResource($resource);
    }

    /**
     * Store a new blog post.
     *
     * @return JsonResponse
     */
    public function store(BaseFormRequest $request)
    {
        try {
            DB::beginTransaction();

            $stored = $this->service->store($request->all());

            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();

            return $this->_badRequestError(['errors' => $e->errors()]);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->_genericError(['message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
        }

        return $this->_created([
            'data' => $this->jsonResource::make($stored),
        ]);
    }

    /**
     * Generic database update method.
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function update(BaseFormRequest $request, $id)
    {
        $resource = $this->service->show($id);

        if (!$resource) {
            return $this->_notFoundError(['message' => __('messages.api.error.not.found')]);
        }

        try {
            $updated = $this->service->update($resource, $request->all());
        } catch (ValidationException $e) {
            return $this->_badRequestError(['errors' => $e->errors()]);
        } catch (\Exception $e) {

            return $this->_genericError(['message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
        }

        return $this->_success([
            'data' => $this->jsonResource::make($updated),
        ]);
    }

    /**
     * Generic database destroy method.
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $resource = $this->service->show($id);

        if (!$resource) {
            return $this->_notFoundError(['message' => __('messages.api.error.not.found')]);
        }

        try {
            $this->service->destroy($resource);
        } catch (\Exception $e) {

            return $this->_genericError(['message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
        }

        return $this->_noContent();
    }

    /**
     * Default Success Response.
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    protected function _success($data)
    {
        $data = array_merge([
            'success' => true,
        ], $data);

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Default Created Response.
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    protected function _created($data)
    {
        $data = array_merge([
            'success' => true,
        ], $data);

        return response()->json($data, Response::HTTP_CREATED);
    }

    /**
     * Default No Content Response.
     *
     * @return JsonResponse
     */
    protected function _noContent()
    {
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Default Bad Request Response.
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    protected function _badRequestError($data)
    {
        $data = array_merge([
            'success' => false,
        ], $data);

        return response()->json($data, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Default Not Found Response.
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    protected function _notFoundError($data)
    {
        $data = array_merge([
            'success' => false,
        ], $data);

        return response()->json($data, Response::HTTP_NOT_FOUND);
    }

    /**
     * Default Generic Error Response.
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    protected function _genericError($data)
    {
        if (!config('app.debug')) {
            $data = ['message' => __('getin::messages.api.error.500')];
        }

        $data = array_merge([
            'success' => false,
        ], $data);

        return response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
