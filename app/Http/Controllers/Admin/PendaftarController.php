<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPendaftarRequest;
use App\Http\Requests\StorePendaftarRequest;
use App\Http\Requests\UpdatePendaftarRequest;
use App\Models\Pendaftar;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PendaftarController extends Controller
{
    use MediaUploadingTrait;
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('pendaftar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pendaftars = Pendaftar::all();

        return view('admin.pendaftars.index', compact('pendaftars'));
    }

    public function create()
    {
        abort_if(Gate::denies('pendaftar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.pendaftars.create');
    }

    public function store(StorePendaftarRequest $request)
    {
        $pendaftar = Pendaftar::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $pendaftar->id]);
        }

        return redirect()->route('admin.pendaftars.index');
    }

    public function edit(Pendaftar $pendaftar)
    {
        abort_if(Gate::denies('pendaftar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.pendaftars.edit', compact('pendaftar'));
    }

    public function update(UpdatePendaftarRequest $request, Pendaftar $pendaftar)
    {
        $pendaftar->update($request->all());

        return redirect()->route('admin.pendaftars.index');
    }

    public function show(Pendaftar $pendaftar)
    {
        abort_if(Gate::denies('pendaftar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.pendaftars.show', compact('pendaftar'));
    }

    public function destroy(Pendaftar $pendaftar)
    {
        abort_if(Gate::denies('pendaftar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pendaftar->delete();

        return back();
    }

    public function massDestroy(MassDestroyPendaftarRequest $request)
    {
        Pendaftar::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('pendaftar_create') && Gate::denies('pendaftar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Pendaftar();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
