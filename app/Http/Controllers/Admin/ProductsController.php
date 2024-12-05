<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Section;


class ProductsController extends Controller
{
    public function products(){
       // Session::put('page','sections');
        $products = Product::with([
        'section'=>function($query){$query->select('id','name');},
        'category'=>function($query){$query->select('id','category_name');}
        ])->get()->toArray();
        //  dd($products);
        return view('admin.products.products')->with(compact('products'));
    }
    public function updateProductStatus(Request $request){
        if($request->ajax()){
           $data = $request->all();
           // echo "<pre>"; print_r($data); die;
           if($data['status']=="Active"){
               $status = 0;
           }else{
               $status = 1;
           }
           Product::where('id',$data['product_id'])->update(['status'=>$status]);
        //    return response()->json(['status' => $status, 'product_id' => $data['product_id']]);
           $response = ['status' => $status, 'product_id' => $data['product_id']];
           return response()->json($response, 200);
        }
       }
       public function deleteProduct($id){
        //حذف منتج
        Product::where('id',$id)->delete();
        $message = "Product has been deleted successfully!"; 
        return redirect()->back()->with('success_message',$message);
  }

  public function addEditProduct(Request $request, $id=null){
    if($id==""){
      //اضافة منتج
      $title = "Add Product";
         } else{
        //تعديل منتج
        $title = "Edit Category";
        }

        // الحصول على الاقسام مع الفئات و الفئات الفرعية 
        $categories = Section::with('categories')->get()->toArray();
        dd($categories);

        return view('admin.products.add_edit_product')->with(compact('title'));
  }

  // public function addEditProduct(Request $request, $id=null){
  //   //  Session::put('page','categories');
  //     if($id==""){
  //       //اضافة منتج
  //       $title = "Add Product";
  //       $category = new Product;
  //       $getCategories = array();
  //       $message = "Category added successfully!";
  //     }else{
  //      //تعديل منتج
  //      $title = "Edit Category";
  //      $category = Category::find($id);
  //       //    echo "<pre>"; print_r($category['category_name']); die;
  //       $getCategories = Category::with('subcategories')->where(['parent_id'=>0,
  //       'section_id'=>$category['section_id']])->get();
  //       $message = "Category updated successfully!";
  //       }

  //       if($request->isMethod('post')){
  //           $data = $request->all();
  //       //    echo "<pre>"; print_r($data); die; 
  //       $rules = [
  //           'category_name' => 'required|regex:/^[\pL\s\-]+$/u',
  //           'section_id' => 'required',
  //           'url' => 'required',

  //     ];
  //     $this->validate($request,$rules);
  //       //اعطاء قيمة للعمود في حال ترك فارغ لو رقم 
  //       // او عمل "" اذا كان كلام او تغيره null  من فاعدة البيانات 
  //     if($data['category_discount']==""){
  //       $data['category_discount'] = 0;}
  
  //       //رفع صورة الفئة 
  //        if($request->hasFile('category_image')){
  //            $image_tmp = $request->file('category_image');
  //             if($image_tmp->isValid()){
  //               // الحصول على ملحق الصورة
  //                $extension = $image_tmp->getClientOriginalExtension();  
  //               //انشاء اسم صورة جديد 
  //                $imageName = rand(111,99999).'.'.$extension; 
  //                $imagePath = 'front/images/category_images/'.$imageName;
  //               //رفع الصورة 
  //               Image::make($image_tmp)->save($imagePath);
  //               $category->category_image = $imageName;
  //             }
  //           }else{
  //               $category->category_image = "";
  //        }

  //        $category->section_id = $data['section_id'];
  //        $category->parent_id = $data['parent_id'];
  //        $category->category_name = $data['category_name'];
  //        $category->category_discount = $data['category_discount'];
  //        $category->description = $data['description'];
  //        $category->url = $data['url'];       
  //        $category->meta_title = $data['meta_title'];
  //        $category->meta_description = $data['meta_description'];
  //        $category->meta_keywords = $data['meta_keywords'];
  //        $category->status = 1;
  //        $category->save();

  //        return redirect('admin/categories')->with('success_message',$message);

  //    }
          
  //     //الحصول على كل الاقسام 
  //     $getSections = Section::get()->toArray();

  //     return view('admin.products.add_edit_product')->with(compact('title',
  //     'category','getSections','getCategories'));
  // }


}
