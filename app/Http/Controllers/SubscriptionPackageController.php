<?php


namespace App\Http\Controllers;

use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $request->ajax() jika ada permintaan dari ajax js, permintaan proses datatables dipanggil melalui ajax javascript di blade
        if ($request->ajax()) {
            $model = SubscriptionPackage::query();

            return DataTables::eloquent($model)
                // fungsinya yaitu menambahkan nomor urut dari 1-2-dst
                ->addIndexColumn()
                // memformat tampilan harga menjadi format Rupiah
                ->editColumn('price', function ($data) { 
                    return 'Rp. ' . number_format($data->price, 0, ',', '.');
                })
                // menampilkan warna dalam bentuk badge
                ->editColumn('color', function ($data) {
                    return '<span class="badge" style="background-color: ' . e($data->color) . '; color: #fff; text-shadow: 0 0 2px #000;">' . e($data->color) . '</span>';
                })
                // menambahkan data selain yang ada di database: btn aksi edit dan hapus
                ->addColumn('action', function ($data) {
                    $editUrl = route('admin.subscription-package.edit', $data->id);
                    $deleteUrl = route('admin.subscription-package.destroy', $data->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    $btnEdit = '<a href="' . $editUrl . '"
                            class="btn btn-sm btn-warning">Edit</a>';

                    $btnDelete = ' <form action="' . $deleteUrl . '"
                                    method="POST" class="d-inline">
                                    ' . $csrf . $method . '
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm(\'Apakah anda yakin ingin menghapus paket ini?\')">
                                        Hapus
                                    </button>
                                </form>';

                    return $btnEdit . $btnDelete;
                })
                // menyimpan dari addColumn/editColumn yang ada html di dalamnya
                ->rawColumns(['color', 'action'])
                ->toJson();
        }

        return view('admin.subscription-package.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subscription-package.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'color' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
        ]);

        SubscriptionPackage::create($validatedData);

        return redirect()->route('admin.subscription-package.index')->with('success', 'Paket langganan berhasil ditambahkan');
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
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);
        return view('admin.subscription-package.edit', compact('subscriptionPackage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'color' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],

        ]);

        $subscriptionPackage->update($validatedData);

        return redirect()->route('admin.subscription-package.index')->with('success', 'Paket langganan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subscriptionPackage = SubscriptionPackage::findOrFail($id);
        $subscriptionPackage->delete();

        return redirect()->route('admin.subscription-package.index')->with('success', 'Paket langganan berhasil dihapus');
    }
}
