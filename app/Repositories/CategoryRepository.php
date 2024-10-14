<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;

use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface

{
    public function getAllCategories($perPage = 10)
    {
        return Category::paginate($perPage);
    }

    // Skip by offset
    // public function getAllCategories($offset = 0, $limit = 10)
    // {
    //     return Category::skip($offset)->take($limit)->get();
    // }

    public function getCategoryById($categoryId)

    {

        return Category::findOrFail($categoryId);
    }

    public function deleteCategory($categoryId)

    {

        Category::destroy($categoryId);
    }

    public function createCategory(array $categoryDetails)

    {

        return Category::create($categoryDetails);
    }

    public function updateCategory($categoryId, array $categoryDetails)

    {
        $category = Category::findOrFail($categoryId);
        $category->update($categoryDetails);

        return $category;
    }

    public function getAllActiveCategories()

    {

        return Category::where('status', 1);
    }
}
