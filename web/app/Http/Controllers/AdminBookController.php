<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Support\Facades\Validator;

class AdminBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with(['author', 'category', 'publisher'])->paginate(15);
        return view('admin.books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authors = Author::all();
        $categories = Category::all();
        $publishers = Publisher::all();
        return view('admin.books.create', compact('authors', 'categories', 'publishers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'nullable|exists:authors,id',
            'author_new' => 'nullable|string|max:255',
            'publisher_id' => 'nullable|exists:publishers,id',
            'publisher_new' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['stock'] = $data['stock'] ?? 0;

        // Handle new author creation if provided
        if (!empty($data['author_new'])) {
            $author = Author::firstOrCreate(['name' => $data['author_new']]);
            $data['author_id'] = $author->id;
        }

        // Handle new publisher creation if provided
        if (!empty($data['publisher_new'])) {
            $publisher = Publisher::firstOrCreate(['name' => $data['publisher_new']]);
            $data['publisher_id'] = $publisher->id;
        }

        // Ensure we have required IDs
        if (empty($data['author_id'])) {
            $defaultAuthor = Author::firstOrCreate(['name' => 'Không rõ']);
            $data['author_id'] = $defaultAuthor->id;
        }

        if (empty($data['publisher_id'])) {
            return redirect()->back()->withErrors(['publisher_id' => 'Vui lòng chọn hoặc nhập nhà xuất bản.'])->withInput();
        }

        // Handle cover upload if provided
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover_path'] = $path;
        }

        unset($data['author_new'], $data['publisher_new'], $data['cover']);

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Thêm sách thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::findOrFail($id);
        $authors = Author::all();
        $categories = Category::all();
        $publishers = Publisher::all();
        return view('admin.books.edit', compact('book', 'authors', 'categories', 'publishers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'nullable|exists:authors,id',
            'author_new' => 'nullable|string|max:255',
            'publisher_id' => 'nullable|exists:publishers,id',
            'publisher_new' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['stock'] = $data['stock'] ?? 0;

        // Xử lý tác giả mới
        if (!empty($data['author_new'])) {
            $author = Author::firstOrCreate(['name' => $data['author_new']]);
            $data['author_id'] = $author->id;
        }

        // Xử lý nhà xuất bản mới
        if (!empty($data['publisher_new'])) {
            $publisher = Publisher::firstOrCreate(['name' => $data['publisher_new']]);
            $data['publisher_id'] = $publisher->id;
        }

        // Đảm bảo có author_id và publisher_id
        if (empty($data['author_id'])) {
            $defaultAuthor = Author::firstOrCreate(['name' => 'Không rõ']);
            $data['author_id'] = $defaultAuthor->id;
        }

        if (empty($data['publisher_id'])) {
            return redirect()->back()->withErrors(['publisher_id' => 'Vui lòng chọn hoặc nhập nhà xuất bản.'])->withInput();
        }

        // Xử lý upload ảnh bìa mới
        if ($request->hasFile('cover')) {
            // Xóa ảnh cũ nếu có
            if ($book->cover_path && file_exists(public_path('storage/' . $book->cover_path))) {
                unlink(public_path('storage/' . $book->cover_path));
            }
            
            // Lưu ảnh mới
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover_path'] = $path;
        }

        // Xóa các key không cần thiết
        unset($data['cover'], $data['author_new'], $data['publisher_new']);

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Cập nhật sách thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        
        // Delete cover image if exists
        if ($book->cover_path && file_exists(public_path('storage/' . $book->cover_path))) {
            unlink(public_path('storage/' . $book->cover_path));
        }
        
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Xóa sách thành công.');
    }
}
