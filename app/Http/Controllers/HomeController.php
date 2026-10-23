<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\SubscriptionPackage;

class HomeController extends Controller
{

    public function index()
    {
        // Mengambil 3 paket langganan terbaru dari database
        $subscriptionPackages = SubscriptionPackage::latest()->take(3)->get();

        // Data buku terbaru dan buku gratis untuk slider beranda
        $latestBooks = Book::orderBy('created_at', 'desc')->limit(10)->get();
        $freeBooks = Book::where('price', 0)->orderBy('created_at', 'desc')->limit(6)->get();

        // Mengirimkan data ke view home.blade.php
        return view('home', compact('subscriptionPackages', 'latestBooks', 'freeBooks'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $books = Book::when($query, function ($q) use ($query) {
            $q->where(function ($sub) use ($query) {
                $sub->where('title', 'like', '%' . $query . '%')
                    ->orWhere('writer', 'like', '%' . $query . '%')
                    ->orWhere('publisher', 'like', '%' . $query . '%')
                    ->orWhereHas('bookCategory', function ($catQuery) use ($query) {
                        $catQuery->where('name', 'like', '%' . $query . '%');
                    });
            });
        })->with('bookCategory')->latest()->get();

        return view('search_result', compact('books', 'query'));
    }

    public function category(Request $request, $id)
    {
        // Mengambil data kategori buku yang dipilih (berdasarkan ID atau Nama)
        $category = BookCategory::where('id', $id)->orWhere('name', $id)->firstOrFail();

        // Mengambil input query pencarian dan pilihan sorting dari form di category_books.blade.php
        $query = $request->input('search');
        $sort = $request->input('sort', 'latest'); // default sorting adalah 'latest'

        // Mengambil daftar buku sesuai dengan kategori yang dipilih
        $books = Book::where('book_category_id', $category->id)
            // Filter pencarian berdasarkan judul buku atau nama penulis jika ada input pencarian
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('title', 'like', '  ' . $query . '%')
                        ->orWhere('writer', 'like', '%' . $query . '%');
                });
            })
            // Filter pengurutan (sorting) data buku
            ->when($sort, function ($q) use ($sort) {
                if ($sort === 'cheapest') {
                    // Sorting berdasarkan harga termurah (Ascending: kecil ke besar)
                    $q->orderBy('price', 'asc');
                } elseif ($sort === 'expensive') {
                    // Sorting berdasarkan harga termahal (Descending: besar ke kecil)
                    $q->orderBy('price', 'desc');
                } else {
                    // Sorting buku terbaru (Descending: waktu input terbaru)
                    $q->orderBy('created_at', 'desc');
                }
            }, function ($q) {
                // Fallback default jika parameter sort tidak dikirimkan
                $q->orderBy('created_at', 'desc');
            })
            ->get();

        // Mengirimkan variabel ke view category_books.blade.php
        return view('category_books', compact('category', 'books', 'query', 'sort'));
    }

    /**
     * Menampilkan detail paket langganan dan daftar buku yang tertaut 
     * Data diambil dari tabel subscription_package_books
     */
    public function subscriptionPackageDetail(Request $request, $id)
    {
        // Mengambil data paket langganan berdasarkan ID
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);

        // Mengambil input query pencarian dari search bar pada paket ini
        $query = $request->input('search');

        // Mengambil daftar buku yang tertaut pada paket langganan dari tabel subscription_package_books
        $books = $subscriptionPackage->books()
            // Filter pencarian berdasarkan judul buku jika ada input search
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%');
            })
            ->with('bookCategory')
            ->get();

        return view('user.subscription-packages.show', compact('subscriptionPackage', 'books', 'query'));
    }
}