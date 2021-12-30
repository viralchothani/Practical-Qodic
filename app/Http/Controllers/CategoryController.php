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
        //get parent category
        $category = Category::where('parent_category',NULL)->get();
        return view('category',compact('category'));
    }

    /**
     * Create Category/Sub-Category
     */
    public function create(Request $request)
    {
        //Validation
        $validator = Validator::make($request->all(), [
            'category' => 'required|unique:category',
        ]);

        $input = $request->all();

        if ($validator->passes()) {
            //Create category
            $model = new Category();
            $model->category = $input['category'];
            $model->parent_category = $input['sub_category'];
            $model->save();

            //Success response
            $data = [];
            $data['message'] = 'New category added';
            $data['category'] = $input['category'];
            $data['parent_category'] = $input['sub_category'];

            return response()->json($data);
        }
        //Error while create category
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    /**
     * Auto-Complete Category Search
     */
    public function getCategory(Request $request)
    {
        //search value
        $search = $request->search;

        $fields = ['category'];
        //Get category query
        if ($search != '') {
            $query = Category::where(function ($where) use ($fields, $search) {
                foreach ($fields as $field)
                    $where->orWhere($field, 'like', "%{$search}%");
            })->orderBy('category', 'ASC')->get()->toArray();
        } else {
            $query = Category::orderBy('category', 'ASC')->get()->toArray();
        }

        $response = array();
        foreach ($query as $item) {
            //Push value in response array
            $response[] = array("label" => $item['category']);
        }
        //category listing data response
        return response()->json(['status' => 200, 'data' => $response]);
    }
}
