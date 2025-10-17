<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ], [
            'name.required' => 'กรุณากรอกชื่อหมวดหมู่',
            'name.string' => 'ชื่อหมวดหมู่ต้องเป็นข้อความ',
            'name.max' => 'ชื่อหมวดหมู่ต้องมีความยาวไม่เกิน 100 ตัวอักษร',
            'name.unique' => 'มีหมวดหมู่นี้อยู่แล้ว',
        ]);

        Category::create($request->only('name'));

        return redirect()->route('admin.categories.index')->with('ok', 'สร้างหมวดหมู่ใหม่สำเร็จ');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
        ], [
            'name.required' => 'กรุณากรอกชื่อหมวดหมู่',
            'name.string' => 'ชื่อหมวดหมู่ต้องเป็นข้อความ',
            'name.max' => 'ชื่อหมวดหมู่ต้องมีความยาวไม่เกิน 100 ตัวอักษร',
            'name.unique' => 'มีหมวดหมู่นี้อยู่แล้ว',
        ]);

        $category->update($request->only('name'));

        return redirect()->route('admin.categories.index')->with('ok', 'อัปเดตหมวดหมู่สำเร็จ');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'ไม่สามารถลบหมวดหมู่ได้ เนื่องจากมีสินค้าใช้งานอยู่');
        }
        
        $category->delete();

        return redirect()->route('admin.categories.index')->with('ok', 'ลบหมวดหมู่สำเร็จ');
    }
}
