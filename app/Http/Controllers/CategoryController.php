<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Interfaces\CategoryRepositoryInterface;

use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;

use Illuminate\Http\Response;

class CategoryController extends Controller

{

    private CategoryRepositoryInterface $categoryRepository;
    private $resource;

    public function __construct(CategoryRepositoryInterface $categoryRepository)

    {

        $this->categoryRepository = $categoryRepository;
        $this->resource = CategoryResource::class;
    }

    public function index(): JsonResponse

    {
        $categories = $this->categoryRepository->getAllCategories();
        return response()->json([
            'data' => CategoryResource::collection($categories)
        ]);
    }

    public function store(Request $request): JsonResponse

    {
        $categoryDetails = $request->all();
        $category = $this->categoryRepository->createCategory($categoryDetails);
        return response()->json(
            [
                'data' => new CategoryResource($category)
            ],
            Response::HTTP_CREATED
        );
    }

    public function show(Request $request): JsonResponse

    {

        $categoryId = $request->route('id');
        $category = $this->categoryRepository->getCategoryById($categoryId);
        return response()->json([
            'data' => new CategoryResource($category)
        ]);
    }

    public function update(Request $request, $id): JsonResponse

    {
        $categoryDetails = $request->all();
        $category = $this->categoryRepository->updateCategory($id, $categoryDetails);
        return response()->json([
            'data' => new CategoryResource($category)
        ]);
    }

    public function destroy($id): JsonResponse

    {
        if (!empty($id)) {
            $this->categoryRepository->deleteCategory($id);
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
