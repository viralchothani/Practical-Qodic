<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Category Listing
     */
    public function index(){
        $category = Category::where('parent_category',NULL)->get();
        return view('category',compact('category'));
    }

    /**
     * Create Category/Sub-Category
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|unique:category',
        ]);

        $input = $request->all();

        if ($validator->passes()) {
            $model = new Category();
            $model->category = $input['category'];
            $model->parent_category = $input['sub_category'];
            $model->save();

            $data = [];
            $data['message'] = 'New category added';
            $data['category'] = $input['category'];
            $data['parent_category'] = $input['sub_category'];

            return response()->json($data);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    /**
     * Auto-Complete Category Search
     */
    public function autocompleteSearch(Request $request)
    {
        $search = $request->search;

        $fields = ['category'];
        if ($search != '') {
            $query = Category::where(function ($q) use ($fields, $search) {
                foreach ($fields as $field)
                    $q->orWhere($field, 'like', "%{$search}%");
            })->orderBy('category', 'ASC')->get()->toArray();
        } else {
            $query = Category::orderBy('category', 'ASC')->get()->toArray();
        }

        $response = array();
        foreach ($query as $item) {
            $response[] = array("label" => $item['category']);
        }
        return response()->json(['status' => 200, 'data' => $response]);
    }
}
