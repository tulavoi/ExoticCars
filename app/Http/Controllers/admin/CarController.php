<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Manufacturer;
use App\Models\Category;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;

class CarController extends Controller
{
    // Chuyển đến trang car list
    public function index()
    {
        $cars = Car::with('manufacturer', 'category')->paginate(10);

        return view('admin.car.list', compact('cars'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    // Chuyển đến trang add
    public function add()
    {
        $manufacturers = Manufacturer::all();
        $categories = Category::all();
        return view('admin.car.add', compact('manufacturers', 'categories'));
    }

    // Xử lý chức năng add
    public function store(Request $request)
    {
        $car = new Car();
        $car->name = $request->input('name');
        $car->manufacturer_id = $request->input('manufacturer_id');
        $car->category_id = $request->input('category_id');
        $car->price = $request->input('price');
        $car->quantity = $request->input('quantity');
        $car->description = $request->input('description');
        $car->producing_year = $request->input('producing_year');
        $car->slug = \Str::slug($car->name . ' ' . $car->producing_year);

        $MAX_IMAGE = 5;

        for ($i = 1; $i <= $MAX_IMAGE; $i++) {
            if ($request->hasFile('img' . $i)) {
                $file = $request->file('img' . $i);
                $extension = $file->getClientOriginalExtension();
                $fileName = uniqid() . '.' . $extension;
                $file->move('images/cars/', $fileName);
                $imgJson[] = $fileName;
            }
        }

        $car->images = json_encode($imgJson);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid() . '.' . $extension;
            $file->move('images/cars/', $fileName);
            $car->avatar = $fileName;
        }

        // dd($car);
        try {
            $car->save();
            return redirect()->route('cars.index')->with('success', 'The car has been added successfully.');
        } catch (QueryException $e) {
            return redirect()->route('cars.index')->with('error', 'Failed to add car. Please try again.');
        }
    }

    // Chuyển đến trang edit
    public function edit($id)
    {
        $car = Car::with('manufacturer', 'category')->find($id);
        if (!$car) {
            abort(404);
        }

        $manufacturers = Manufacturer::all();
        $categories = Category::all();

        return view('admin.car.edit', compact('car', 'manufacturers', 'categories'));
    }

    // Xử lý chức năng update
    public function update(Request $request, $id)
    {
        $car = Car::find($id);
        $car->name = $request->input('name');
        $car->manufacturer_id = $request->input('manufacturer_id');
        $car->category_id = $request->input('category_id');
        $car->price = $request->input('price');
        $car->quantity = $request->input('quantity');
        $car->description = $request->input('description');
        $car->producing_year = $request->input('producing_year');
        $car->slug = \Str::slug($car->name . ' ' . $car->producing_year);

        /*
         * Bị lỗi không cập nhật hình ảnh mới (avatar) của xe.
         *
         * Phân tích: Đoạn mã này nhằm mục đích cập nhật hình ảnh avatar cho xe. Tuy nhiên, sau khi tải lên hình ảnh mới,
         * không có sự cập nhật nào được thực hiện trên đối tượng xe. Do đó, dù hình ảnh đã được tải lên thành công,
         * nhưng hình ảnh của xe vẫn là hình ảnh cũ.
         */

        if ($request->hasFile('avatar')) {
            $oldImg = 'images/cars/' . $car->avatar;
            if (File::exists($oldImg)) {
                File::delete($oldImg);
            }
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid() . '.' . $extension;
            $file->move('images/cars/', $fileName);
            $car->avatar = $fileName;
        }

        dd($car);

        try {
            $car->save();
            return redirect()->route('cars.index')->with('success', 'The car has been edited successfully.');
        } catch (QueryException $e) {
            return redirect()->route('cars.index')->with('error', 'Failed to edit car. Please try again.');
        }
    }

    // Xử lý chức năng delete
    public function delete($id)
    {
        $car = Car::find($id);
        $avatar = 'images/cars/' . $car->avatar;
        if (\File::exists($avatar)) {
            \File::delete($avatar);
        }

        try {
            $car->delete();
            return redirect()->back()->with('success', 'The car has been deleted successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Failed to delete car. Please try again.');
        }
    }
}
