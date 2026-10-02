<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = Book::query();
            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('coverImg', function ($row) {
                    //asset: mengambil file yang ada di folder public
                    return '<img src="' . asset($row->cover) . '" width="100" class="d-block mx-auto" />';
                })
                ->editColumn('price', function ($row) {
                    return 'Rp. ' . number_format($row->price, 0, ',', '.');
                })
                ->editColumn('book_category_id', function ($row) {
                    //bookcategory diambil dari func relasi yg ada di modek book
                    return $row->bookCategory->name;
                })
                ->addColumn('action', function ($row) {
                    //buat button action
                    $btnEdit = '<a href="' . route('admin.books.edit', $row->id) . '" class="btn btn-primary me-2">Edit</a>';
                    $btnDelete = '<form method="POST" action="' . route('admin.books.destroy', $row->id) . '">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger">Delete</button></form>';
                    $btnDetail = '<button type="button" class="btn btn-primary"  id="btn-detail"
                        data-cover="' . asset($row->cover) . '"
                        data-title="' . $row->title . '"
                        data-category="' . $row->bookCategory->name . '"
                        data-price="' . $row->price . '"
                        data-writer="' . $row->writer . '"
                        data-publisher="' . $row->publisher . '"
                        data-language="' . $row->language . '"
                        data-page-of-book="' . $row->page_of_book . '"
                        data-release-date="' . date('d M Y', strtotime($row->release_date)) . '"
                        data-description="' . htmlspecialchars($row->description, ENT_QUOTES, 'UTF-8') . '"
                    >Detail</button>';

                    return $btnEdit . $btnDelete . $btnDetail;
                })
                ->rawColumns(['coverImg', 'action'])
                ->toJson();
        }
        return view('admin.books.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bookCategories = BookCategory::all();
        return view('admin.books.create', compact('bookCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            //mimes opsi jenis dile yang di upload
            'cover' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,svg,avif'],
            'title' => ['required'],
            'writer' => ['required'],
            'publisher' => ['required'],
            'price' => ['required', 'numeric'],
            'language' => ['required'],
            'description' => ['nullable'],
            //exists:table,field =data  harus uda ada di tabble book_categories, field id
            'book_category_id' => ['required', 'exists:book_categories,id'],
            'page_of_book' => ['required', 'numeric'],
            'release_date' => ['required', 'date'],

        ]);

        if ($request->File('cover')) {
            $cover = $request->file('cover');
            //bikin nama file dari waktu gambar diupload sidambungkan "." eksteknsi file :21412(contoh hasil nama file)
            $namaFile = time() . '.' . $cover->getClientOriginalExtension();
            //disk :penyimoanan storage (nanti diganti ke layanan cloud setelah dihosting)
            //putFileAs : (nama folder, file yang disimpen, namafile)
            Storage::disk('public')->putFileAs('covers', $cover, $namaFile);
            //ambil alamat gambar untuk disimpan di database, timpa data cover di valisadi  dengan alamat gambar yang baru diupload
            $validatedData['cover'] = Storage::url('covers/' . $namaFile);
        }

        Book::create($validatedData);
        return redirect()->route('admin.books.index')->with('success', 'berhasil membuat data Buku baru');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
