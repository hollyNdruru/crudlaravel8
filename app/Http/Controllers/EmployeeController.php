<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;
use PDF;


class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('search')){
            $data = Employee::where('nama','LIKE','%' .$request->search.'%')->paginate(5);
        }else {
            $data = Employee::paginate(5);
        }
        // $data = Employee::all();//sebelum menggunkan pagenate
      
        return view('datapegawai', compact('data'));
    }
    public function tambahpegawai()
    {
        return view('tambahdata');
    }
    public function insertdata(Request $request)
    {
        // dd($request->all());
        // Employee::create($request->all());
        $data = Employee::create($request->all());
        if($request->hasFile('foto')){
            $request->file('foto')->move('fotopegawai/', $request->file('foto')->getClientOriginalname());
            $data->foto = $request->file('foto')->getClientOriginalname();
            $data->save();
        }

        return redirect()->route('pegawai')->with('success','Data berhasil di Tambahkan');
    }
    public function tampilkandata($id)
    {
        $data = Employee::find($id);
        // dd($data);
        return view('tampilkandata',compact('data'));
    }
    public function updatedata(Request $request, $id)
    {
        $data = Employee::find($id);
        $data->update($request->all());
        return redirect()->route('pegawai')->with('success','Data berhasil di Update');

    }
    public function delete($id)
    {
        $data = Employee::find($id);
        $data->delete();
        return redirect()->route('pegawai')->with('success','Data berhasil di Hapus');

    }
    public function exportpdf()
    {
        $data = Employee::all();
        view()->share('data', $data);
        $pdf = PDF::loadview('datapegawai-pdf');
        return $pdf->download('data.pdf');
    }
    public function exportexcel(){
        return Excel::download(new EmployeeExport, 'datapegawai.xlsx');
    }

}
