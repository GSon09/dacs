@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Books</h1>
        <a href="{{ route('books.create') }}" class="btn btn-primary">Add Book</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Publisher</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                        <tr>
                            <td>{{ $book->id }}</td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author?->name }}</td>
                            <td>{{ $book->category?->name }}</td>
                            <td>{{ $book->publisher?->name }}</td>
                            <td>{{ number_format($book->price, 0, ',', '.') }}\u20ab</td>
                            <td>{{ $book->stock }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-secondary">Edit</a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this book?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $books->links() }}</div>
        </div>
    </div>
</div>
@endsection
