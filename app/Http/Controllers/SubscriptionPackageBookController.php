<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubscriptionPackage;
use App\Models\Book;
use App\Models\SubscriptionPackageBook;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionPackageBookController extends Controller
{
    public function show($subscriptionPackageId, Request $request)
    {
        $subscriptionPackage = SubscriptionPackage::with('books')->findOrFail($subscriptionPackageId);

        if (request()->ajax()) {
            $model = Book::with('bookCategory');
            $existingBookIds = $subscriptionPackage->books->pluck('id')->toArray();

            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('coverImg', function ($row) {
                    return '<img src="' . asset($row->cover) . '" width="100" class="d-block mx-auto" />';
                })
                ->editColumn('price', function ($row) {
                    return 'Rp. ' . number_format($row->price, 0, ',', '.');
                })
                ->editColumn('title', fn($row) => e($row->title))
                ->editColumn('writer', fn($row) => e($row->writer))
                ->editColumn('book_category_id', function ($row) {
                    return e($row->bookCategory->name);
                })
                ->addColumn('checkAdded', function ($row) use ($existingBookIds) {
                    return in_array($row->id, $existingBookIds) ? 'Ya' : 'Tidak';
                })
                ->rawColumns(['coverImg'])
                ->toJson();
        }

        return view('admin.subscription-package.books', compact('subscriptionPackage'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'book_id' => 'required|array',
            'book_id.*' => 'exists:books,id',
        ], [
            'book_id.required' => 'Pilih setidaknya satu buku untuk ditambahkan ke paket langganan.',
        ]);

        foreach ($request->book_id as $bookId) {
            SubscriptionPackageBook::firstOrCreate([
                'subscription_package_id' => $id,
                'book_id' => $bookId,
            ]);
        }

        return redirect()->back()->with('success', 'Buku berhasil ditambahkan ke paket langganan');
    }

}
