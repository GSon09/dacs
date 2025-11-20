<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('index');
    }

    // Hiển thị tất cả sản phẩm với bộ lọc
    public function allProducts(Request $request)
    {
        $query = \App\Models\Book::with(['author', 'publisher', 'category']);
        // Keyword search across title, description, author, publisher, category
        $keyword = $request->get('q');
        if ($keyword) {
            $like = '%' . $keyword . '%';
            $query->where(function ($q) use ($like) {
                $q->where('title', 'like', $like)
                  ->orWhere('description', 'like', $like)
                  ->orWhereHas('author', function ($qa) use ($like) {
                      $qa->where('name', 'like', $like);
                  })
                  ->orWhereHas('publisher', function ($qp) use ($like) {
                      $qp->where('name', 'like', $like);
                  })
                  ->orWhereHas('category', function ($qc) use ($like) {
                      $qc->where('name', 'like', $like);
                  });
            });
        }
        
        // Lọc theo category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo author (accept numeric id or name string)
        if ($request->has('author_id') && $request->author_id) {
            $authorFilter = $request->author_id;
            if (is_numeric($authorFilter)) {
                $query->where('author_id', $authorFilter);
            } else {
                $likeA = '%' . $authorFilter . '%';
                $query->whereHas('author', function ($qa) use ($likeA) {
                    $qa->where('name', 'like', $likeA);
                });
            }
        }

        // Lọc theo publisher (accept numeric id or name string)
        if ($request->has('publisher_id') && $request->publisher_id) {
            $publisherFilter = $request->publisher_id;
            if (is_numeric($publisherFilter)) {
                $query->where('publisher_id', $publisherFilter);
            } else {
                $likeP = '%' . $publisherFilter . '%';
                $query->whereHas('publisher', function ($qp) use ($likeP) {
                    $qp->where('name', 'like', $likeP);
                });
            }
        }
        
        // Lọc theo giá
        if ($request->has('price_range') && $request->price_range) {
            switch ($request->price_range) {
                case '1':
                    $query->whereBetween('price', [0, 150000]);
                    break;
                case '2':
                    $query->whereBetween('price', [150000, 300000]);
                    break;
                case '3':
                    $query->whereBetween('price', [300000, 500000]);
                    break;
                case '4':
                    $query->whereBetween('price', [500000, 700000]);
                    break;
                case '5':
                    $query->where('price', '>=', 700000);
                    break;
            }
        }
        
        // Lọc theo type (thể loại)
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Sắp xếp
        if ($request->has('sort') && $request->sort) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        }
        
        $books = $query->paginate($request->get('per_page', 24));
        $categories = \App\Models\Category::all();
        
        return view('all-products', compact('books', 'categories'));
    }

    // Hiển thị sách theo danh mục
    public function category($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        $books = \App\Models\Book::where('category_id', $id)->with(['author', 'publisher'])->paginate(12);
        $categories = \App\Models\Category::all();
        
        // Debug log
        \Log::info('Category page accessed', [
            'category_id' => $id,
            'category_name' => $category->name,
            'books_count' => $books->count()
        ]);
        
        return view('category', compact('category', 'books', 'categories'));
    }

    // Hiển thị chi tiết sách
    public function bookDetail($id)
    {
        $book = \App\Models\Book::with(['author', 'publisher', 'category'])->findOrFail($id);
        
        // Lấy sách liên quan cùng danh mục
        $relatedBooks = \App\Models\Book::where('category_id', $book->category_id)
            ->where('id', '!=', $id)
            ->with(['author', 'publisher'])
            ->limit(4)
            ->get();
        
        return view('book-detail', compact('book', 'relatedBooks'));
    }
}