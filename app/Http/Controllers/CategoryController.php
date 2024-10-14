<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
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

    public function index(Request $request): JsonResponse

    {
        $perPage = $request->query('per_page', 2);
        $categories = $this->categoryRepository->getAllCategories($perPage);

        return response()->json([
            'data' => CategoryResource::collection($categories),
            'meta' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ],
        ]);

        // Using offset for Skip
        // $offset = $request->query('offset', 2); // Default to 0 if not specified
        // $limit = $request->query('limit', 10); // Default to 10 if not specified

        // $categories = $this->categoryRepository->getAllCategories($offset, $limit);

        // return response()->json([
        //     'data' => CategoryResource::collection($categories),
        //     // Optionally add pagination metadata
        //     'meta' => [
        //         'offset' => (int) $offset,
        //         'limit' => (int) $limit,
        //         // You can include total count if needed
        //         'total' => Category::count(),
        //     ],
        // ]);
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
