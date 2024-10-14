<?php

namespace App\Http\Controllers;

use App\Interfaces\CategoryRepositoryInterface;

use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;

use Illuminate\Http\Response;

class CategoryController extends Controller

{

    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)

    {

        $this->categoryRepository = $categoryRepository;
    }

    public function index(): JsonResponse

    {

        return response()->json([

            'data' => $this->categoryRepository->getAllCategories()

        ]);
    }

    public function store(Request $request): JsonResponse

    {
        $categoryDetails = $request->all();

        return response()->json(

            [

                'data' => $this->categoryRepository->createCategory($categoryDetails)

            ],

            Response::HTTP_CREATED

        );
    }

    public function show(Request $request): JsonResponse

    {

        $categoryId = $request->route('id');

        return response()->json([

            'data' => $this->categoryRepository->getCategoryById($categoryId)

        ]);
    }

    public function update(Request $request, $id): JsonResponse

    {
        $categoryDetails = $request->all();

        return response()->json([

            'data' => $this->categoryRepository->updateCategory($id, $categoryDetails)

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
